<?php

use OpenSRS\domains\authentication\AuthenticationSendPassword;

/**
 * @group authentication
 * @group AuthenticationSendPassword
 */
class AuthenticationSendPasswordTest extends PHPUnit_Framework_TestCase
{
    protected $fund = "authSendPassword";

    protected $validSubmission = array(
        "data" => array(
            /**
             * Required
             *
             * domain_name: The domain name for
             *   which the password is to be sent
             * send_to: which contact the password
             *   should be sent to, either 'owner'
             *   or 'admin' (default)
             * sub_user: indicate if password
             *   should be sent to the sub-user
             *   of the domain.
             *   0 = do not send to sub-user
             *   1 = send to sub-user (error returned
             *       if set to 1 but there is no sub-user
             *       associated to the domain)
             */
            "domain_name" => "",
            "send_to" => "",
            "sub_user" => ""
            )
        );

    /**
     * Valid submission should complete with no
     * exception thrown
     *
     * @return void
     *
     * @group validsubmission
     */
    public function testValidSubmission() {
        $data = json_decode( json_encode($this->validSubmission) );
        $data->data->domain_name = "phptest" . time() . ".com";

        $ns = new AuthenticationSendPassword( 'array', $data );

        $this->assertTrue( $ns instanceof AuthenticationSendPassword );
    }

    /**
     * Data Provider for Invalid Submission test
     */
    function submissionFields() {
        return array(
            'missing domain_name' => array('domain_name'),
            );
    }

    /**
     * Invalid submission should throw an exception
     *
     * @return void
     *
     * @dataProvider submissionFields
     * @group invalidsubmission
     */
    public function testInvalidSubmissionFieldsMissing( $field, $parent = 'data', $message = null ) {
        $data = json_decode( json_encode($this->validSubmission) );

        $data->data->domain_name = "phptest" . time() . ".com";

        if(is_null($message)){
          $this->setExpectedExceptionRegExp(
              'OpenSRS\Exception',
              "/$field.*not defined/"
              );
        }
        else {
          $this->setExpectedExceptionRegExp(
              'OpenSRS\Exception',
              "/$message/"
              );
        }



        // clear field being tested
        if(is_null($parent)){
            unset( $data->$field );
        }
        else{
            unset( $data->$parent->$field );
        }

        $ns = new AuthenticationSendPassword( 'array', $data );
    }
}
