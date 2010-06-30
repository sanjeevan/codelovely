<?php

/**
 * job actions.
 *
 * @package    frostty
 * @subpackage job
 * @author     Sanjeevan Ambalavanar
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class jobActions extends sfActions
{
  public function executeGetJobStatus(sfWebRequest $request)
  {
    $queue = new RedisJobQueue(ThumbnailScraperWorker::QUEUE_NAME);
    $job_data = $queue->getJobMeta($request->getParameter('jobid'));
    
    $this->getResponse()->setContentType('application/json');
    return $this->renderText(json_encode($job_data));
  }
}
