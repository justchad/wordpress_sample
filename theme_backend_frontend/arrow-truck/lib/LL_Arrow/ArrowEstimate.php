<?php
/*
 * Calculations are documented in clients the drive folder
 *
 * Please note:
 * Because we're using gravity forms, I utilize custom classes on the inputs
 * in the backend to determine if a field should be part of the calculation.
 * Field : Classname
 * Truck Price : calc-price
 * Mileage : calc-miles
 * Risk Rating Questions : calc-field
 *
 * As of right now, this is unfinished as we learned at the last minute on my last day that there
 * is a new api necessary to complete the formulas from the drive document. I'm sure some of the current
 * logic will remain a little intact, and the functions to get the data from the gravity form are unlikely
 * to change. Good luck
 */

class ArrowEstimate
{
  private $mile_rate = 900000;
  private $frequency = 12;


  public function __construct( $data, $form ) {
    $this->data   = $data;
    $this->fields = collect( $form['fields'] );
    $this->miles = $this->getMiles();
    $this->cost = $this->getCost();
    $this->downpayment = $this->cost * 0.26;
    $this->number_of_payments = $this->calcNumberOfPayments();
    $this->risk_score = $this->getRiskScore();
    $this->rate = $this->calcRate();
    $this->denominator = $this->calcDenominator();
  }

  public static function init( $key, $entry, $form ) {
    $estimate = new ArrowEstimate( $entry, $form );
    return $estimate->calcPayment();
  }

  private function calcNumberOfPayments() {
    $floor = $this->mile_rate - $this->miles;
    return floor( $floor / 10000 );
  }

  private function mapYearlyRate() {
    $rate = 0;
    switch ($this->risk_score) {
      case 31:
        $rate = 9.00;
        break;
      case 30:
        $rate = 9.30;
        break;
      case 29:
        $rate = 9.60;
        break;
      case 28:
        $rate = 9.90;
        break;
      case 27:
        $rate = 10.20;
        break;
      case 26:
        $rate = 10.50;
        break;
      case 25:
        $rate = 10.80;
        break;
      case 24:
        $rate = 11.10;
        break;
      case 23:
        $rate = 11.40;
        break;
      case 22:
        $rate = 11.70;
        break;
      case 21:
        $rate = 12.00;
        break;
      case 20:
        $rate = 12.40;
        break;
      case 19:
        $rate = 12.80;
        break;
      case 18:
        $rate = 13.20;
        break;
      case 17:
        $rate = 13.60;
        break;
      case 16:
        $rate = 14.00;
        break;
      case 15:
        $rate = 14.40;
        break;
      case 14:
        $rate = 14.80;
        break;
      case 13:
        $rate = 15.20;
        break;
      case 12:
        $rate = 15.60;
        break;
      case 11:
        $rate = 16.00;
        break;
      case 10:
        $rate = 16.40;
        break;
      case 9:
        $rate = 16.80;
        break;
      case 8:
        $rate = 17.20;
        break;
      case 7:
        $rate = 17.60;
        break;
      case 6:
        $rate = 18.00;
        break;
      case 5:
        $rate = 18.40;
        break;
      case 4:
        $rate = 18.80;
        break;
      case 3:
        $rate = 19.20;
        break;
      case 2:
        $rate = 19.60;
        break;
      case 1:
        $rate = 20.00;
        break;
      case 0:
        $rate = 20.40;
        break;
      case -1:
        $rate = 20.80;
        break;
      case -2:
        $rate = 21.20;
        break;
      case -3:
        $rate = 21.60;
        break;
      case -4:
        $rate = 22.00;
        break;
      case -5:
        $rate = 22.40;
        break;
      case -6:
        $rate = 22.80;
        break;
      case -7:
        $rate = 23.20;
        break;
      case -8:
        $rate = 23.60;
        break;
      default:
        $rate = 24.00;
        break;
    }

    return number_format( $rate, 2 );
  }

  private function calcRate() {
    $this->yearly_rate = $this->mapYearlyRate();
    return $this->yearly_rate / 100 / $this->frequency;
  }

  private function calcDenominator() {
    return pow( (1 + $this->rate), $this->number_of_payments ) - 1;
  }

  private function getCost() {
    $field = $this->fields->first( function( $field ) {
      return strpos( $field->cssClass, 'calc-price' ) !== false;
    } );

    if ( !$field )
      return 0;

    $key = 'input_'.$field->id;
    return intval( rgar( $this->data, $field->id ) );
  }

  private function getMiles() {
    $field = $this->fields->first( function( $field ) {
      return strpos( $field->cssClass, 'calc-miles' ) !== false;
    } );

    if ( !$field )
      return 0;

    $key = 'input_'.$field->id;
    return intval( rgar( $this->data, $field->id ) );
  }

  public function calcPayment() {
    return array(
      'down'    => '$'.number_format( $this->downpayment, 2 ),
      'monthly' => '$'. number_format( ( $this->rate + ( $this->rate / $this->denominator ) ) * ( $this->cost - $this->downpayment ), 2 )
    );
  }

  /*
   * Collect all fields that are set to be calc-field, get the
   * correspsonding value from $data, then sum the values
   */
  private function getRiskScore() {
    $risk_value = $this->fields->filter( function( $field ) {
      return strpos( $field->cssClass, 'calc-field' ) !== false;
    } )->map( function( $field ) {
      $key = 'input_'.$field->id;
      return intval( rgar( $this->data, $field->id ) );
    } )->values()->sum();

    return $risk_value + $this->getAdvance() + $this->getInvestment();
  }

  /*
   * Logic from Arrow
   * advance = 1 - (downpayment / costOfTruck ) * 100
   * then get risk value based on advance %
   */
  private function getAdvance() {
    $rate = number_format( ( 1 - ( $this->downpayment / $this->cost ) ) * 100, 1 );
    $risk_score = 0;
    if ( $rate <= 75 ) {
      $risk_score = 5;
    } elseif ( $rate >= 75.1 && $rate <= 85 ) {
      $risk_score = 3;
    } elseif ( $rate >= 85.1 && $rate <= 90 ) {
      $risk_score = 0;
    } elseif ( $rate >= 90.1 && $rate <= 100 ) {
      $risk_score = -10;
    } elseif ( $rate > 100 ) {
      $risk_score = -15;
    }

    return $risk_score;
  }

  /*
   * Logic from Arrow
   * investment = (downpayment / costOfTruck ) * 100
   * then get risk value based on investment %
   */
  private function getInvestment() {
    $rate;

    if($this->cost == 0){
      $rate = number_format( ( $this->downpayment) * 100, 1 );
    }else{
      $rate = number_format( ( $this->downpayment / $this->cost ) * 100, 1 );
    }

    $risk_score = 0;
    if ( $rate >= 25 ) {
      $risk_score = 8;
    } elseif ( $rate >= 15 && $rate < 25 ) {
      $risk_score = 5;
    } elseif ( $rate >= 10 && $rate < 15 ) {
      $risk_score = 0;
    } elseif ( $rate >= 5 && $rate < 10 ) {
      $risk_score = -3;
    } elseif ( $rate < 5 ) {
      $risk_score = -5;
    }

    return $risk_score;
  }
}
