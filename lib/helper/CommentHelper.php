<?php

function render_comments($comments, Article $article, $user = null)
{
  $parent = 0;
  return thread_comments($comments, 0);
}

function get_child_comments(&$comments, $parent = 0)
{
  $t = array();
  
  foreach ($comments as $k => $c)
  {
    if ($c['reply_id'] == $parent)
    {
      $t[] = $c;
      
      // unset parent, so we don't have to loop through all comments for this
      // article at each depth
      unset($comments[$k]);
    } 
  }
  
  return $t;
}

function thread_comments($comments, $parent = 0)
{
  $children = get_child_comments($comments, $parent);
  
  foreach ($children as $key => $comment)
  {
    include_partial('comment/comment', array('comment' => $comment));
    thread_comments($comments, $comment['id']);
  }  
}