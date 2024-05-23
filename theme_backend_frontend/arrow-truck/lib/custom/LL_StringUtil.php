<?php
/**
 * A class of utilities for dealing with strings.
 * originally taken from woocommerce.
 */
final class LL_StringUtil {

  /**
   * Checks to see whether or not a string starts with another.
   *
   * @param string $string The string we want to check.
   * @param string $starts_with The string we're looking for at the start of $string.
   * @param bool   $case_sensitive Indicates whether the comparison should be case-sensitive.
   *
   * @return bool True if the $string starts with $starts_with, false otherwise.
   */
  public static function starts_with( string $string, string $starts_with, bool $case_sensitive = true ): bool {
    $len = strlen( $starts_with );
    if ( $len > strlen( $string ) ) {
      return false;
    }

    $string = substr( $string, 0, $len );

    if ( $case_sensitive ) {
      return strcmp( $string, $starts_with ) === 0;
    }

    return strcasecmp( $string, $starts_with ) === 0;
  }

  /**
   * Checks to see whether or not a string ends with another.
   *
   * @param string $string The string we want to check.
   * @param string $ends_with The string we're looking for at the end of $string.
   * @param bool   $case_sensitive Indicates whether the comparison should be case-sensitive.
   *
   * @return bool True if the $string ends with $ends_with, false otherwise.
   */
  public static function ends_with( string $string, string $ends_with, bool $case_sensitive = true ): bool {
    $len = strlen( $ends_with );
    if ( $len > strlen( $string ) ) {
      return false;
    }

    $string = substr( $string, -$len );

    if ( $case_sensitive ) {
      return strcmp( $string, $ends_with ) === 0;
    }

    return strcasecmp( $string, $ends_with ) === 0;
  }

  public static function sentance_case( string $string ): string {
    return ucwords( strtolower( $string ) );
  }

  public static function lowercase( string $string ): string {
    return strtolower( $string );
  }

  public static function uppercase( string $string ): string {
    return strtoupper( $string );
  }

  public static function from_array( string $string, $delimiter ): array {
    return implode( $delimiter, $string );
  }

  public static function to_array( string $string, $delimiter=',' ): array {
    return explode( $delimiter, $string );
  }

  public static function contains( string $string, string $contains ): string {
    return strpos( $string, $contains ) !== false;
  }

  public static function replace( $string, $replace, $search ): string {
    return str_replace( $string, $replace, $search );
  }
}
