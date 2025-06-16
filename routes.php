<?php

namespace application\routes;

use \engine\WebService;

class Log extends WebService
{
  // Configs:
  private const REQUIRE_AUTHENTICATION = false;
  private const REQUIRE_PERMISSION = false;
  private const PERMISSION_KEY = "";

  public function init()
  {
    $this->setAntiXsrfValidation(false);

    // PHPINFO ENDPOINT:
    $this->addEndpoint('GET', '/phpinfo', function () {
      // Authenticate User Login:
      if (self::REQUIRE_AUTHENTICATION)
        if (!$this->getService('iam/session')->authenticate()) return $this->response->withStatus(401);

      // Authenticate User Permissions:
      if (self::REQUIRE_PERMISSION)
        $this->getService('iam/permission')->canExecute(self::PERMISSION_KEY);

      phpinfo();
    });

    // LOG ENDPOINTS:
    $this->addEndpoint('GET', '/db-error', function () {
      // Authenticate User Login:
      if (self::REQUIRE_AUTHENTICATION)
        if (!$this->getService('iam/session')->authenticate()) return $this->response->withStatus(401);

      // Authenticate User Permissions:
      if (self::REQUIRE_PERMISSION)
        $this->getService('iam/permission')->canExecute(self::PERMISSION_KEY);

      return $this->response->withData($this->getService('log_service')->dbErrorLog(), false);
    });

    $this->addEndpoint('GET', '/application-error', function () {
      // Authenticate User Login:
      if (self::REQUIRE_AUTHENTICATION)
        if (!$this->getService('iam/session')->authenticate()) return $this->response->withStatus(401);

      // Authenticate User Permissions:
      if (self::REQUIRE_PERMISSION)
        $this->getService('iam/permission')->canExecute(self::PERMISSION_KEY);

      return $this->response->withData($this->getService('log_service')->applicationErrorLog(), false);
    });

    $this->addEndpoint('GET', '/security', function () {
      // Authenticate User Login:
      if (self::REQUIRE_AUTHENTICATION)
        if (!$this->getService('iam/session')->authenticate()) return $this->response->withStatus(401);

      // Authenticate User Permissions:
      if (self::REQUIRE_PERMISSION)
        $this->getService('iam/permission')->canExecute(self::PERMISSION_KEY);

      return $this->response->withData($this->getService('log_service')->securityLog(), false);
    });

    $this->addEndpoint('GET', '/sys-error', function () {
      // Authenticate User Login:
      if (self::REQUIRE_AUTHENTICATION)
        if (!$this->getService('iam/session')->authenticate()) return $this->response->withStatus(401);

      // Authenticate User Permissions:
      if (self::REQUIRE_PERMISSION)
        $this->getService('iam/permission')->canExecute(self::PERMISSION_KEY);

      return $this->response->withData($this->getService('log_service')->systemErrorLog(), false);
    });

    $this->addEndpoint('GET', '/curl', function () {
      // Authenticate User Login:
      if (self::REQUIRE_AUTHENTICATION)
        if (!$this->getService('iam/session')->authenticate()) return $this->response->withStatus(401);

      // Authenticate User Permissions:
      if (self::REQUIRE_PERMISSION)
        $this->getService('iam/permission')->canExecute(self::PERMISSION_KEY);

      return $this->response->withData($this->getService('log_service')->curlLog(), false);
    });

    $this->addEndpoint('GET', '/event-error', function () {
      // Authenticate User Login:
      if (self::REQUIRE_AUTHENTICATION)
        if (!$this->getService('iam/session')->authenticate()) return $this->response->withStatus(401);

      // Authenticate User Permissions:
      if (self::REQUIRE_PERMISSION)
        $this->getService('iam/permission')->canExecute(self::PERMISSION_KEY);

      return $this->response->withData($this->getService('log_service')->eventErrorLog(), false);
    });

    $this->addEndpoint('GET', '/server', function () {
      // Authenticate User Login:
      if (self::REQUIRE_AUTHENTICATION)
        if (!$this->getService('iam/session')->authenticate()) return $this->response->withStatus(401);

      // Authenticate User Permissions:
      if (self::REQUIRE_PERMISSION)
        $this->getService('iam/permission')->canExecute(self::PERMISSION_KEY);

      return $this->response->withData($this->getService('log_service')->serverErrorLog(), false);
    });

    $this->addEndpoint('GET', '/custom/?logName?', function ($input) {
      // Authenticate User Login:
      if (self::REQUIRE_AUTHENTICATION)
        if (!$this->getService('iam/session')->authenticate()) return $this->response->withStatus(401);

      // Authenticate User Permissions:
      if (self::REQUIRE_PERMISSION)
        $this->getService('iam/permission')->canExecute(self::PERMISSION_KEY);

      return $this->response->withData($this->getService('log_service')->customErrorLog($input['logName']), false);
    });

    $this->addEndpoint('GET', '', function () {
      // Authenticate User Login:
      if (self::REQUIRE_AUTHENTICATION)
        if (!$this->getService('iam/session')->authenticate()) return $this->response->withStatus(401);

      // Authenticate User Permissions:
      if (self::REQUIRE_PERMISSION)
        $this->getService('iam/permission')->canExecute(self::PERMISSION_KEY);

      return $this->response->withData((object) [
        'server' => $this->getService('log_service')->serverErrorLog(),
        'system-error' => $this->getService('log_service')->systemErrorLog(),
        'application-error' => $this->getService('log_service')->applicationErrorLog(),
        'database-error' => $this->getService('log_service')->dbErrorLog(),
        'event-error' => $this->getService('log_service')->eventErrorLog()
      ], false);
    });
  }
}
