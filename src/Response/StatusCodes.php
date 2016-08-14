<?php
namespace GooglePlacesAPIAutocomplete\Response;

class StatusCodes {

  /**
   * Indicates that no errors occurred and at least one result was returned.
   * @type {String}
   */
  const OK = "OK";

  /**
   * Indicates that the search was successful but returned no results. This may
   * occur if the search was passed a bounds in a remote location.
   * @type {String}
   */
  const ZERO_RESULTS = "ZERO_RESULTS";

  /**
   * Indicates that you are over your quota.
   * @type {String}
   */
  const OVER_QUERY_LIMIT = "OVER_QUERY_LIMIT";

  /**
   * Indicates that your request was denied, generally because of lack of an
   * invalid key parameter.
   * @type {String}
   */
  const REQUEST_DENIED = "REQUEST_DENIED";

  /**
   * Generally indicates that the input parameter is missing.
   * @type {String}
   */
  const INVALID_REQUEST = "INVALID_REQUEST";

}