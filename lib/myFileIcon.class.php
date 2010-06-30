<?php

/**
 * helper class to display the right icon for different filetypes
 * 
 * @version CVS: $Id: myFileIcon.class.php,v 1.12 2009/12/11 17:33:47 sanjeevan Exp $
 *
 */
class myFileIcon
{
  /**
   * maps file extensions to iconstype icons
   *
   * @var array
   */
  static $icon_map = array(
    'zip' => 'icons/page_white_compressed.png',
    'rar' => 'icons/page_white_compressed.png',
  
    // office
    'doc'   => 'icons/page_white_word.png',
    'docx'  => 'icons/page_white_word.png',
    'xls'   => 'icons/page_excel.png',
    'xlsx'  => 'icons/page_excel.png',
    'ppt'   => 'icons/page_white_powerpoint.png',
    'pptx'  => 'icons/page_white_powerpoint.png',
    'pub'   => 'icons/page_white_office.png',
    'mdb'   => 'icons/document-access.png',
    
    'txt' => 'icons/page_white_text.png',
    'pdf' => 'icons/page_white_acrobat.png',
    'rtf' => 'icons/page_white_text.png',
  
    // images
    'jpg' => 'icons/page_white_picture.png',
    'jpeg'=> 'icons/page_white_picture.png',
    'gif' => 'icons/page_white_picture.png',
    'png' => 'icons/page_white_picture.png',
    'bmp' => 'icons/page_white_picture.png',
    'psd' => 'icons/photoshop.png',
    
    // audio/video
    'wav' => 'icons/sound.png',
    'mp3' => 'icons/sound.png',
    'mov' => 'icons/television.png',
    'avi' => 'icons/television.png',
    'flv' => 'icons/page_white_flash.png',
    'wmv' => 'icons/television.png',
    '3gp' => 'icons/television.png',
  
    // misc
    'swf' => 'icons/page_white_flash.png'
  ); 
    
  /**
   * get icon for a file extension
   *
   * @param string $extension
   * @return string
   */
  static function getIconTagForExtension($extension)
  {
    $path = '';
    
    if (in_array($extension, array_keys(self::$icon_map)))
    {
      return image_tag(image_path(self::$icon_map[$extension]));
    }
    
    return image_tag(image_path('icons/package.png'));
  }
  
  /**
   * get path to mimetype icon for this file
   *
   * @param string $filename
   * @return string
   */
  static function getPath($filename)
  {
    $extension = myUtil::getFileExtension($filename);

    if (in_array($extension, array_keys(self::$icon_map)))
    {
      return image_path(self::$icon_map[$extension]);  
    }
    else
    {
      return image_path('icons/package.png');
    }
  }
  
  /**
   * get html image tag for file's mimetype icon
   *
   * @param string $filename
   * @param array $options
   * @return string
   */
  static function getIconTag($filename, $options = null)
  {
    return image_tag(self::getPath($filename), $options);
  }
  
}
?>