<?php
/**
 * Contains class for HTML
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/
 * @package PHPIDS
 * @license LGPL
 * @since 2012/10/05
 * @version 0.7
 */
class HTML
{
  /**
   * Constant defining the XHTML transitional doctype
   */
  const DOC_TYPE_XHMTL_TRANSITIONAL = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";

  /**
   * Constant defining the XHTML strict doctype
   */
  const DOC_TYPE_XHMTL_STRICT = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
  /**
   * Constant defining the XHTML Frameset doctype
   */

  const DOC_TYPE_XHMTL_FRAMESET = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";

  /**
   * Constant defining text field
   */
  const TYPE_TEXT = 'text';

  /**
   * Constant defining textareas
   */
  const TYPE_TEXT_AREA = 'textarea';

  /**
   * Constant defining the content type text with charset UTF-8
   */
  const META_CONTENTTYPE = "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";


  public function  __construct() {
  } // __construct

  /**
   * validates an url string
   *
   * @param string $url The url
   * @return boolean Whether the url is valid, or not
   */
  public function isValidURL($url)
  {
    if (substr($url, 0, 1) == '#') {
      $result = true;
    } else {
      $result = preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
    }
 
    return $result;
  } // isValidURL

     /**
   * Returns the doc type for XHTML transitional
   *
   * @return string
   */
  public function getDocTypeXHTMLTransitional()
  {
    return self::DOC_TYPE_XHMTL_TRANSITIONAL;
  } // getDocTypeXHTMLTransitional

  /**
   * Returns the doc type for XHTML strict
   *
   * @return string
   */
  public function getDocTypeXHTMLStrict()
  {
    return self::DOC_TYPE_XHMTL_STRICT;
  } // getDocTypeXHTMLStrict

  /**
   * Returns the doc type for XHTML frameset
   *
   * @return string
   */
  public function getDocTypeXHTMLFrameset()
  {
    return self::DOC_TYPE_XHMTL_FRAMESET;
  } // getDocTypeXHTMLFrameset

  /**
   * Returns the HTML title
   *
   * @param string $caption The title string
   * @return string
   */
  public function getTitle($caption)
  {
    return "  <title>$caption</title>\n";
  } // getTitle

  /**
   * Returns the meta tag for content type text/html, charset UTF-8
   *
   * @return string
   */
  public function getMetaContentType()
  {
    return self::META_CONTENTTYPE;
  }

  /**
   * Returns the Type for TEXT
   *
   * @return string
   */
  public function getTYPE_TEXT()
  {
    return self::TYPE_TEXT;
  } // getTYPE_TEXT

  /**
   * Returns the Type for TEXT AREA
   *
   * @return string
   */
  public function getTYPE_TEXT_AREA()
  {
    return self::TYPE_TEXT_AREA;
  } // getTYPE_TEXT_AREA

  /**
   * THe function returns the fields of forms encapsulated in a p tag
   *
   * @param string $sType The field type, allowed types are text and textarea
   * @param string $sCaption The field caption
   * @param string $sDataName The name of the data field
   * @param boolean $bReadOnly whether a field is readonly or not
   * @param string $sHTMLID If not empty, the value is used to set an HTML ID
   * @param integer $iSize The size of the text field i.e. width
   * @param integer $iCols The count of columns for textareas
   * @param integer $iRows The count of rows for textareas
   * @return string
   */
  public Function getFormField($sType, $sCaption, $sDataName, $bReadOnly,
          $sHTMLID='' , $iSize = 40, $iCols = 40, $iRows = 3)
  {
    $sReadOnly = '';

    if ($bReadOnly) {
      $sReadOnly = "readonly=\"readonly\" ";
    }

    if (!empty ($sHTMLID)) {
      $sHTMLID = 'id="' . htmlentities($sHTMLID) . '" ';
    }

    switch ($sType) {
      case 'text':

        // The result is a text field
        $result = "                        '<p>" . $sCaption ."<br />' + \n"
                 ."                          '<input name=\"$sDataName\" "
                                                 ."type=\"text\" "
                                                 .$sReadOnly
                                                 ."size= \"$iSize\" "
                                                 .$sHTMLID
                                                 ."value=\"' + data.$sDataName + '\" />' + \n"
                 ."                        '</p>' + \n";

        break; // text

      case 'textarea':

        // The result is a textarea
        $result = "                        '<p>" . $sCaption ."<br />' + \n"
                 ."                          '<textarea name=\"$sDataName\" "
                                                ."type=\"text\" "
                                                ."readonly=\"readonly\" "
                                                ."cols= \"$iCols\" "
                                                ."rows=\"$iRows\">' + data.$sDataName + '</textarea>' + \n"
                 ."                        '</p>' + \n";

        break; // textarea

      default:
        $result = '';

        break; // default
    }


    return $result;
  } // getFormField

  /**
   * Returns a completly formatted HTML link
   *
   * @param string $address The address, where the link refers to
   * @param string $identifier Default: empty. The HTML id for the link.
   * @param string $styleClass Default: empty. The CSS style class for this link.
   * @param string $title Default: empty. The title of the link.
   * @param string $newWindow Default: false. Whether the link should be opened in
   *               a new window, or not.
   * @param string $text Default: empty. The link text, if empty, the address
   *               is used.
   * @return string
   */
  public function getLink($address, $identifier='', $styleClass='', $title='',
          $newWindow=false, $text='')
  {
    if ($this->isValidURL($address)) {
      if (!empty($styleClass)) {
        $styleClass = 'class="' . $styleClass . '" ';
      }

      if (!empty($identifier)) {
        $identifier = 'id="' . $identifier . '" ' ;
      }

      if (!empty($title)) {
        $title = 'title="' . $title . '"';
      }

      if ($newWindow) {
        $newWindow = 'target="_blank" ';
      }

      if (empty($text)) {
        $text = $address;
      }

      $result = '<a href="' . $address . '" '
               . $identifier
               . $styleClass
               . $title
               . $newWindow
               . '>'
               . $text
               . '</a>';
    } else {
        throw new Exception('The given address is invalid!');
    }

    return $result;
  } // getLink

  /**
   * Returns a text with bold font weight
   *
   * @param string $text
   * @return string
   */
  public function getStrong($text)
  {
    return '<strong>' . $text .'</strong>';
  } // getStrong

  /**
   * Returns a HTML headline
   *
   * @param int $type headline number
   * @param string $text headline text
   * @param string $class headline CSS class, Default: EmpyStr
   * @param string $id HTML unique identifier, Default: EmpyStr
   */
  public function getH($type, $text, $class='', $id='')
  {
    if (!empty($id)) {
      $id = " id=\"$id\"";
    }
    if (!empty($class)) {
      $class = " class=\"$class\"";
    }

    if (is_numeric($type)) {
      $result = '<h' . $type . $id . $class . '>' . $text . '</h' . $type. ">\n";
    } else {
        $result = '<h1'. $id . $class . '>' . $text . "</h1>\n";
    }

    return $result;
  } // getH

  /**
   * Returns a HTML div element
   *
   * @param string $content paragraph text
   * @param string $class paragraph CSS class, Default: EmpyStr
   * @param string $id HTML unique identifier, Default: EmpyStr
   */
  public function getDIV($content, $class='', $id='')
  {
    if (!empty($id)) {
      $id = " id=\"$id\"";
    }
    if (!empty($class)) {
      $class = " class=\"$class\"";
    }

    $result = '<div'. $id . $class . '>' . $content . "</div>\n";

    return $result;
  } // getDIV

  /**
   * Returns a HTML paragraph
   *
   * @param string $text paragraph text
   * @param string $class paragraph CSS class, Default: EmpyStr
   * @param string $id HTML unique identifier, Default: EmpyStr
   */
  public function getP($text, $class='', $id='')
  {
    if (!empty($id)) {
      $id = " id=\"$id\"";
    }
    if (!empty($class)) {
      $class = " class=\"$class\"";
    }

    $result = '<p'. $id . $class . '>' . $text . "</p>\n";

    return $result;
  } // getP

  /**
   * Returns a HTML span
   *
   * @param string $text span text
   * @param string $class span CSS class, Default: EmpyStr
   * @param string $id HTML unique identifier, Default: EmpyStr
   */
  public function getSpan($text, $class='', $id='')
  {
    if (!empty($id)) {
      $id = " id=\"$id\"";
    }
    if (!empty($class)) {
      $class = " class=\"$class\"";
    }

    $result = '<span' . $id . $class . '>' . $text . "</span>\n";

    return $result;
  } // getSpan

  /**
   * Returns a HTML linebreak
   *
   * @return string The HTML linebreak
   */
  public function getBR()
  {
    return "<br />\n";
  } // getBR

} // html