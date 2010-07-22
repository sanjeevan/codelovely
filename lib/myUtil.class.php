<?php

class myUtil
{
  const TIME_FORMAT = 'd/m/Y';
  const MYSQL_DATETIME = 'Y-m-d H:i:s';
  
  public static function getClientIpAddress()
  {
    return '0.0.0.0';
  }
  
  /**
   * converts given text from markdown to html
   * 
   * @param string $text
   * @return string
   */
  static function markdown($text)
  {
    static $parser = null;
    
    if (!isset($parser)) {
      $parser = new Markdown_Parser();
      $parser->no_entities = true;
      $parser->no_markup = true;
    }

    return $parser->transform($text);
  }

  /**
   * Return the mime type for file
   *
   * @param string $file
   * @return string
   */
  static function getMimeType($file)
  {
    if (!function_exists('mime_content_type')){
      return trim(exec('file -bi ' . escapeshellarg($file)));
    } else {
      return mime_content_type($file);
    }
  }
  
  /**
   * get symfony web root
   *
   * @return string
   */
  public static function getWebRoot()
  {
    $request = sfContext::getInstance()->getRequest();
    $root = $request->getRelativeUrlRoot();

    $source = 'http';
    
    if ($request->isSecure())
    {
      $source .= 's';
    }
    
    $source .= '://' . $request->getHost() . $root;

    return $source;
  }
  
  /**
   * generates a random string with the specified length
   *
   * @param integer $length
   * @return string
   */
  static function getRandomSalt($length = 14)
  {
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    $i = 0;
    $salt = '';

    srand((double)microtime()*1000000);

    while ($i < $length)
    {
      $num = rand() % 33;
      $tmp = substr($chars, $num, 1);
      $salt = $salt . $tmp;
      $i++;
    }

    return $salt;
  }
    
  public static function stripText($text, $separator = '-')
  {
    // convert special characters
    $text = utf8_decode($text);
    $text = htmlentities($text);
    $text = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/', '$1', $text);
    $text = html_entity_decode($text);

    $text = strtolower($text);

    // strip all non word chars
    $text = preg_replace('/\W/', ' ', $text);

    // replace all white space sections with a separator
    $text = preg_replace('/\ +/', $separator, $text);

    // trim separators
    $text = trim($text, $separator);
    //$text = preg_replace('/\-$/', '', $text);
    //$text = preg_replace('/^\-/', '', $text);

    return $text;
  }
  
  public static function getFileExtension($filename)
  {
    $parts = explode('.', $filename);
    return end($parts);
  }

  public static function UUID()
  {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
  }
}
