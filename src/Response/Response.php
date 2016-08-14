<?php
namespace GooglePlacesAPIAutocomplete\Response;

class Response implements ResponseInterface, \Iterator{

  /**
   * The status of the request.
   *
   * @var string
   */
  private $status;

  /**
   * The error message of the request.
   *
   * @var string
   */
  private $errorMessage;

  /**
   * The predictions.
   *
   * @var Prediction[]
   */
  private $predictions = [];

  /**
   * The position in the predictions array.
   *
   * @var int
   */
  private $position = 0;

  /**
   * Response constructor.
   *
   * @param $response
   * @throws \GooglePlacesAPIAutocomplete\Response\RequestException
   */
  public function __construct($response) {
    $decoded_response = json_decode($response);
    // If not an object we hit some unknown error.
    if (!is_object($decoded_response)) {
      throw new RequestException("Unknown error getting data from Google Places API.");
    }

    $this->status = $decoded_response->status;
    foreach ($decoded_response->predictions as $prediction) {
      $this->predictions []= new Prediction($prediction);
    }
    $this->errorMessage = isset($decoded_response->error_message) ? $decoded_response->error_message : '';

    $this->validateResponse();
  }

  private function validateResponse() {
    // If status code is not OK or ZERO_RESULTS, we hit a defined Places API error
    if (!in_array($this->status, array(StatusCodes::OK, StatusCodes::ZERO_RESULTS))) {
      $errorMessage = strtr("Google responded with status: @status @error_mesage.", array(
        '@status' => $this->status,
        '@error_message' => $this->errorMessage
      ));
      throw new RequestException($errorMessage);
    }
  }

  public function current() {
    return $this->predictions[$this->position];
  }

  public function next() {
    $this->position = $this->position + 1;
  }

  public function key() {
    return $this->position;
  }

  public function valid() {
    return isset($this->predictions[$this->position]);
  }

  public function rewind() {
    $this->position = 0;
  }

}
