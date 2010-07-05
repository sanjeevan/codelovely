<?php

/**
 * Returns symfony route to article
 * 
 * @param Article|array $article
 * @param array $options
 */
function link_to_article($article, $options)
{
  $article = $article instanceof Article ? $article->toArray() : $article;

  switch ($article['flavour']){
    case 'code':
      return link_to($article['title'], '@code?slug=' . $article['slug'], $options);
      break;
    case 'link':
      return link_to($article['title'], '@link?slug=' . $article['slug'], $options);
      break;
    case 'snapshot':
      return link_to($article['title'], '@snapshot?slug=' . $article['slug'], $options);
      break;
    case 'question':
      return link_to($article['title'], '@question?slug=' . $article['slug'], $options);
      break;
    default:
      break;
  }
  
  return false;
}

/**
 * For only messages in the outbox
 * 
 * @param sfMessageOutbox $message
 */
function render_sent_to(sfMessageOutbox $message)
{
  $usernames  = explode(',', $message->getToStr());
  $total      = count($usernames);
  $i          = 0;
  $links = array();
  
  foreach ($usernames as $username){
    $links[] = link_to($username, 'profile/' . trim($username));
    $i++;
  }
  
  return implode(', ', $links);
}

/**
 * For messages in the inbox
 * 
 * @param sfMessageInbox $msg
 */
function render_recipients(sfMessageInbox $msg)
{
  $usernames  = explode(',', $msg->getToStr());
  $usernames[]= $msg->getFromUser()->getUsername();
  $total      = count($usernames);
  $i          = 0;
  $links = array();
  
  foreach ($usernames as $username){
    if ($i == $total - 1){
      $username = end($usernames);
      return implode(', ', $links) . " and " . link_to($username, 'profile/' . trim($username));
    }
    
    $links[] = $prefix . link_to($username, 'profile/' . trim($username));
    $i++;
  }
}

function render_msg_to_from($msg)
{
  $to       = $msg->getToStr();
  $max_len  = 22;
  $opt      = array('style' => 'color: #333; text-decoration: none;');
  $name     = '';

  if (strlen($to) > $max_len){
    $name = substr($to, 0, $max_len) . '..';
  } else {
    $name = $to;
  }
  
  return link_to($name, 'message/show?messageid=' . $msg->getId(), $opt);
}

function render_msg_title($msg)
{
  $max_len = 85;
  $title = '<span>' . $msg->getTitle() . ' - </span><span style="color: #777777;">' . substr($msg->getMessage(), 0, $max_len) . '..</span>';
  return $title;  
}


function render_pagination($pager, $url)
{
  if ($pager->haveToPaginate())
  {
    return digg_style_pagination($pager->getPage(), $pager->getLastPage(), 1, $url);
  }
}

/**
 * better pagination links
 *
 * adapted from:
 *
 * http://www.strangerstudios.com/sandbox/pagination/diggstyle.php
 *
 * @param $page
 * @param $total_pages
 * @param $adjacents
 * @param $targetpage
 * @param $pagestring
 * @return unknown_type
 */
function digg_style_pagination($page = 1, $total_pages = 1, $adjacents = 1, $targetpage = "/", $pagestring = "?page=")
{
  //defaults
  if(!$adjacents) $adjacents = 1;
  if(!$page) $page = 1;

  //$targetpage = null;
  //$pagestring = myUtil::getWebRoot() . '/' . $pagestring;

  //other vars
  $prev = $page - 1;                  //previous page is page - 1
  $next = $page + 1;                  //next page is page + 1
  $lastpage = $total_pages;
  $lpm1 = $lastpage - 1;                //last page minus 1

  /*
   Now we apply our rules and draw the pagination object.
   We're actually saving the code to a variable in case we want to draw it more than once.
   */
  $pagination = "";
  $padding = null;
  $margin = null;
  if($lastpage > 1)
  {
    $pagination .= "<div class=\"pagination\"";
    if($margin || $padding)
    {
      $pagination .= " style=\"";
      if($margin)
      $pagination .= "margin: $margin;";
      if($padding)
      $pagination .= "padding: $padding;";
      $pagination .= "\"";
    }
    $pagination .= ">";

    //previous button
    if ($page > 1)
    $pagination .= "<a href=\"$targetpage$pagestring$prev\">&#171; prev</a>";
    else
    $pagination .= "<span class=\"disabled\">&#171; prev</span>";

    //pages
    if ($lastpage < 7 + ($adjacents * 2)) //not enough pages to bother breaking it up
    {
      for ($counter = 1; $counter <= $lastpage; $counter++)
      {
        if ($counter == $page)
        $pagination .= "<span class=\"current\">$counter</span>";
        else
        $pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";
      }
    }
    elseif($lastpage >= 7 + ($adjacents * 2)) //enough pages to hide some
    {
      //close to beginning; only hide later pages
      if($page < 1 + ($adjacents * 3))
      {
        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
        {
          if ($counter == $page)
          $pagination .= "<span class=\"current\">$counter</span>";
          else
          $pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";
        }
        $pagination .= "<span class=\"elipses\">...</span>";
        $pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
        $pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";
      }
      //in middle; hide some front and some back
      elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
      {
        $pagination .= "<a href=\"" . $targetpage . $pagestring . "1\">1</a>";
        $pagination .= "<a href=\"" . $targetpage . $pagestring . "2\">2</a>";
        $pagination .= "<span class=\"elipses\">...</span>";
        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
        {
          if ($counter == $page)
          $pagination .= "<span class=\"current\">$counter</span>";
          else
          $pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";
        }
        $pagination .= "...";
        $pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
        $pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";
      }
      //close to end; only hide early pages
      else
      {
        $pagination .= "<a href=\"" . $targetpage . $pagestring . "1\">1</a>";
        $pagination .= "<a href=\"" . $targetpage . $pagestring . "2\">2</a>";
        $pagination .= "<span class=\"elipses\">...</span>";
        for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++)
        {
          if ($counter == $page)
          $pagination .= "<span class=\"current\">$counter</span>";
          else
          $pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";
        }
      }
    }

    //next button
    if ($page < $counter - 1)
    $pagination .= "<a href=\"" . $targetpage . $pagestring . $next . "\">next &#187;</a>";
    else
    $pagination .= "<span class=\"disabled\">next &#187;</span>";
    $pagination .= "</div>\n";
  }

  return $pagination;
}