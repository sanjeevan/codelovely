<?php
/*
 * This file is part of the sfImageTransform package.
 * (c) 2007 Stuart <stuart.lowes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * sfImageTextImageMagick class.
 *
 * Adds text to the image.
 *
 * @package sfImageTransform
 * @subpackage transforms
 * @author Robin Corps <robin@ngse.co.uk>
 * @version SVN: $Id$
 */
class sfImageTextImageMagick extends sfImageTransformAbstract
{
  /**
   * Font face.
  */
  protected $font = 'Arial';

  /**
   * Font size.
  */
  protected $size = 10;

  /**
   * Text.
  */
  protected $text = '';

  /**
   * Angel of the text.
  */
  protected $angle = 0;

  /**
   * X coordinate.
  */
  protected $x = 0;

  /**
   * Y coordinate.
  */
  protected $y = 0;

  /**
   * Font Color.
  */
  protected $color = '#000000';

  /**
   * Path to font.
  */
  protected $font_dir = '';

  /**
   * Allowed alignments.
   * 
   * options are:
   *  top
   *  bottom
   *  left
   *  right
   *  middle
   *  center
   *  top-left
   *  top-right
   *  top-center
   *  middle-left
   *  middle-right
   *  middle-center
   *  bottom-left
   *  bottom-right
   *  bottom-center
   *
  */
  protected $alignments = array(
                               'top', 'bottom','left' ,'right', 'middle', 'center',
                               'top-left', 'top-right', 'top-center',
                               'middle-left', 'middle-right', 'middle-center',
                               'bottom-left', 'bottom-right', 'bottom-center',
                                );
  
  /**
   * Text alignment.
  */
  protected $alignment = 'left';
  
  /**
   * Construct an sfImageTextImageMagick object.
   *
   * @param string text to be added to image
   * @param integer x-coordinate
   * @param integer y-coordinate
   * @param integer font size
   * @param string font face
   * @param string font color
   * @param string text alignment
   * @param integer text angle
   *
   */
  public function __construct($text, $x=0, $y=0, $size=10, $font='Arial', $color='#000000', $align="left", $angle=0)
  {
    $this->font_dir = sfConfig::get('app_sfImageTransformPlugin_font_dir','/usr/share/fonts/truetype/msttcorefonts');
    $this->setText($text);
    $this->setX($x);
    $this->setY($y);
    $this->setSize($size);
    $this->setFont($font);
    $this->setColor($color);
    $this->setAlignment($align);
    $this->setAngle($angle);
  }

  /**
   * Sets the text.
   *
   * @param string
   */
  public function setText($text)
  {
    $this->text = $text;
  }

  /**
   * Gets the text.
   *
   * @return string
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * Sets X coordinate.
   *
   * @param integer
   */
  public function setX($x)
  {
    $this->x = $x;
  }

  /**
   * Gets X coordinate.
   *
   * @return integer
   */
  public function getX()
  {
    return $this->x;
  }

  /**
   * Sets Y coordinate.
   *
   * @param integer
   */
  public function setY($y)
  {
    $this->y = $y;
  }

  /**
   * Gets Y coordinate.
   *
   * @return integer
   */
  public function getY()
  {
    return $this->y;
  }

  /**
   * Sets text size.
   *
   * @param integer
   */
  public function setSize($size)
  {
    $this->size = $size;
  }

  /**
   * Gets text size.
   *
   * @return integer
   */
  public function getSize()
  {
    return $this->size;
  }

  /**
   * Sets text alignment.
   *
   * @param string
   * @return boolean
   */
  public function setAlignment($alignment)
  {
    if (in_array($alignment, $this->alignments))
    {
      $this->alignment = $alignment;
      
      return true;
    }
    
    return false;
  }

  /**
   * Gets text alignment.
   *
   * @return string
   */
  public function getAlignment()
  {
    return $this->alignment;
  }

  /**
   * Sets text font.
   *
   * @param string
   */
  public function setFont($font)
  {
    $this->font = str_replace(' ', '_', $font);
  }

  /**
   * Gets text font.
   *
   * @return string
   */
  public function getFont()
  {
    return $this->font;
  }

  /**
   * Sets text color.
   *
   * @param string
   */
  public function setColor($color)
  {
    $this->color = $color;
  }

  /**
   * Gets text color.
   *
   * @return string
   */
  public function getColor()
  {
    return $this->color;
  }

  /**
   * Sets text angle.
   *
   * @param string
   */
  public function setAngle($angle)
  {
    $this->angle = $angle;
  }

  /**
   * Gets text angle.
   *
   * @return string
   */
  public function getAngle()
  {
    return $this->angle;
  }

  /**
   * Apply the transform to the sfImage object.
   *
   * @access protected
   * @param sfImage
   * @return sfImage
   */
  protected function transform(sfImage $image)
  {
  
    $resource = $image->getAdapter()->getHolder();

    $draw = new ImagickDraw();
    $draw->setFont($this->font_dir . '/' . $this->font . '.ttf');
    $draw->setFontSize($this->size);
    $draw->setFillColor( new ImagickPixel( $this->getColor() ) );

    $x = $this->getX();
    $y = $this->getY();
    
    // For now only horizontal text alignment is supported
    if($this->getAngle() == 0)
    {   
      
    }
    $lines = explode('\n', $this->getText());
    
    foreach($lines as $line)
    {
      list($x, $y, $width, $height) = $this->calculateBoxData($line, $resource, $draw, $this->getAlignment(), $this->getX(), $this->getY(), $this->getAngle());      
      $resource->annotateImage($draw, (float)$x, (float)$y, (float)$this->getAngle(), $line);
      $y += $this->getSize();
    }

    return $image;
  }
  
  protected function calculateBoxData($text, $image, $draw, $alignment, $x, $y, $angle)
  {
  
    $align = imagick::ALIGN_LEFT;

    if (strstr($alignment, 'center') !== false)
    {
      $align = imagick::ALIGN_CENTER;
    }

    elseif (strstr($alignment, 'right') !== false)
    {
      $align = imagick::ALIGN_RIGHT;
    }

    $draw->setTextAlignment($align);

    list($width, $height) = $this->getTextDimensions($image, $draw, $text);

    switch ($alignment)
    {
      case 'top':
      case 'top-left':
        $y = $y + $height;
        break;
        
      case 'top-right':
        $y = $y + $height;
        break;
        
      case 'middle-left':
        $y = (int)($y + $height / 2);
        break;
        
      case 'middle-right':
        $y = (int)($y + $height / 2);
        break;
        
      case 'middle-center':
        $y = (int)($y + $height / 2);
    }
        
    return array($x, $y, $width, $height);
  }
  
  protected function getTextDimensions($image, $draw, $text)
  {
    $metrix = $image->queryFontMetrics($draw, $text);

    $width = $metrix["boundingBox"]['x2'] - $metrix["boundingBox"]['x1'];
    $height = $metrix["boundingBox"]['y2'] - $metrix["boundingBox"]['y1'];
    
    return array($width, $height);
  }
}
