<?php


class ArrowTruck
{
  // CONST SRC_URL = 'https://www.arrowtruckhost.com';
  CONST SRC_URL = 'https://arrowtruckservices.com/ArrowAPI/api';
  CONST IMG_URL = 'https://www.arrowtruckhost.com';
  // https://arrowtruckservices.com/ArrowAPI/api/Inventory/250381
  CONST URL = '/inventory';

  public function __construct( $data=array() ) {
    global $post;
    /*
     * If parameter is an object, turn it into
     * a collection, then convert it to an array
     */

// var_error_log($data);

    if ( is_array( $data ) && ll_empty( $data ) ) {
        // var_error_log('->>--------------{> Z <}--------------<<-');
      $data = collect( ll_safe_decode( get_post_meta( $post->ID, 'arrow_data', true ) ) )->all();
      $this->from_post = true;
    } else if ( is_object( $data ) ) {
        // var_error_log('->>--------------{> ZZ <}--------------<<-');
      $data = collect( $data )->all();
      $this->from_post = false;
    }

    array_map( function( $key, $value ) {
      switch ($key) {
        case 'INVPRICE':
          $this->$key = '$'.number_format( $value, 2 );
          $this->price_raw = number_format( $value, 0 );
          $this->price_raw = intval( str_replace( ',', '', $this->price_raw ) );
          break;

        case 'INVMILAG':
          $this->$key = number_format( $value );
          break;

        case 'INVEMCM':
          $this->$key = number_format( $value );
          break;

        case 'INVTSPD':
          $this->$key = $value . ' Speed';
          break;

        case 'INVBED#':
          $this->BEDCNT = $value;
          break;

        case 'INVTRNTY':
          if ( $value == 'A' ) {
            $this->$key = 'Auto';
          } elseif ( $value == 'S' ) {
            $this->$key = 'Speed';
          }
          break;

        case 'INVAXLE':
          if ( $value == 'S' ) {
            $this->$key = 'Single';
          } elseif ( $value == 'T' ) {
            $this->$key = 'Tandem';
          }
          break;

        case 'INVBRAKE':
          if ( $value == 'A' ) {
            $this->$key = 'Air';
          } elseif ( $value = 'H' ) {
            $this->$key = 'Hydraulic';
          }
          break;

        case 'INVFXMDL':
          $this->$key = number_format( $value ) . ' LBS';
          break;

        case 'INVRXMDL':
          $this->$key = number_format( $value ) . ' LBS';
          break;

        case 'INVRATIO':
          $this->$key = substr_replace( $value, '.', strlen($value) - 2, 0 );
          break;

        case 'INVDETAIL':
          $this->$key = ucfirst( strtolower( $value ) );
          break;

        default:
          $this->$key = $value;
          break;
      }

    }, array_keys( $data ), $data );

    // SEE( $this );

    $_year = ( isset( $this->INVYEAR ) ) ? $this->INVYEAR : $this->YEAR;
    $_make = ( isset( $this->EQUDESCR ) ) ? $this->EQUDESCR : $this->MANUFACTURER;
    $_model = ( isset( $this->EQMDESCR ) ) ? $this->EQMDESCR : $this->MODEL;
    $_price = ( isset( $this->price_raw ) ) ? $this->price_raw : $this->PRICE;
    $_mileage = ( isset( $this->INVMILAG ) ) ? $this->INVMILAG : $this->MILEAGE;

    $this->link = home_url() . ArrowTruck::URL . '/'. $this->STOCKNUM;
    $this->name = "{$_year} {$_make} {$_model}";
    $this->estimate_link = arrow_page_url( 'estimate' )."?truck={$this->STOCKNUM}&price={$_price}&mileage=".intval( str_replace( ',', '', $_mileage ) );
    $this->buildImages();
  }

  public function location() {
    return LL_StringUtil::sentance_case( $this->BRANCHNAME ) .', ' . LL_StringUtil::uppercase( ($this->STATE != 'IL') ?  $this->STATE : 'MO' );
  }

  /*
   * Get's either a meta key or Arrow Property
   */
  public function get_post_data( $meta_key, $property ) {
    if ( !$this->wp_post )
      return $this->$property;

    $value = $this->wp_post->$meta_key;
    if ( $value )
      return $value;

    return $this->$property;
  }

  public function get_field( $meta_key, $property ) {
    if ( !$this->ID )
      return $this->$property;

    $value = get_field( $meta_key, $this->ID );
    if ( $value )
      return $value;

    return $this->$property;
  }

  /*
   * Logic provided directly by Arrow for generating URL images.
   * I converted their function to use PHP and use our ArrowTruck object
   */
  private function buildImages() {

    $this->images = [];
    $this->thumbnails = [];
    // $strPhotoPath = ArrowTruck::IMG_URL.'/invImages/001_';
    $strPhotoPath = $this->PHOTOPATH;
    $strDirPath = '';
    $intPhotoNum = 1;
    // Removing INVSTKNO no longer used in API.
    // $strDirPath = $strPhotoPath . $this->INVSTKNO;
    $strDirPath = $strPhotoPath;

    //Checks to see how many photos there are. If this is not set in return defaults to one as set above.
    //1
    if ( isset( $this->INVPCKAG ) ) {
      $intPhotoNum = (int) $this->INVPCKAG;
    }

    //Checks if INVIMAGE is not set.
    //2
    if ( $this->INVIMAGE !== 'Y' ) {
      //Check if INVNDLST is set
      //3
      if ( $this->INVNDLST == 'Y' ) {
        $strDirPath = $strPhotoPath . $this->INVAPRNO;
        if ( isset( $this->INVPCKAG ) ) {
          $intPhotoNum = (int) $this->INVPCKAG;
        }

      } elseif ( $this->FNUM !== '' ) {
          //Check if INVNDLST is not set, check FNUM does not equal an empty string.
          //This is supposed to deal with fleet photos not sure this is being used...
          $strDirPath = ArrowTruck::IMG_URL.'/images/FleetPhotos/'.$this->FNUM;
          $intPhotoNum = (int) $this->FPICS;
      } else {
          //If either condition does not return set the no image.
        $strDirPath = ArrowTruck::IMG_URL.'/images/NoImage2.jpg';
      }
    }

    //One last check to make sure that the photo number is not 0 if it is set it again to 1.
    if ( $intPhotoNum === 0 )
      $intPhotoNum = 1;

    //Set the empty variable Image Name.
    $strImageName = '';

    //If No Image is set, and the path is set to match, set the image and thumnail to no image. All images set to this to begin.
    if ( $strDirPath == ArrowTruck::IMG_URL.'/images/NoImage2.jpg' ) {
      $this->images[] = $strDirPath;
      $this->thumbnails[] = ArrowTruck::IMG_URL.'/images/NoImage2.tb.jpg';
    } else {
      for ($i=0; $i < $intPhotoNum; $i++) {
        if ( $i < 9 ) {

          $strImageName = $strDirPath . 'image0' . ($i + 1) . '.jpg';
          $thumbnailName = $strDirPath . 'image0' . ($i + 1) . '.tb.jpg';
        } else {

          $strImageName = $strDirPath . 'image' . ($i + 1) . '.jpg';
          $thumbnailName = $strDirPath . 'image' . ($i + 1) . '.tb.jpg';
        }

        $this->images[] = $strImageName;
        $this->thumbnails[] = $thumbnailName;
      }
    }

    $this->images = collect( $this->images );
    $this->thumbnails = collect( $this->thumbnails );
  }
}
