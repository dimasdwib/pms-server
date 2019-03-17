<?php

namespace Tests;

trait ApplicationApi
{

  /**
   * Inject JWT Token to request headers;
   * @return class
   */
  protected function apiAs($user, array $headers = [])
  {
      $headers = array_merge([
          'Authorization' => 'Bearer '.\JWTAuth::fromUser($user),
      ], $headers);

      return $this->withHeaders($headers);
  }
}