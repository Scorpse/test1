<?php
/**
 * Created by PhpStorm.
 * User: scorpse
 * Date: 06-Mar-17
 * Time: 3:13 AM
 */

namespace App\Http\Controllers;


class GeneralReportTest extends \TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testgeneralReportValidator()
    {
        $this->get('/report/general?start_date=2017-03-03&end_date=1017-03-04');

        $data = json_decode($this->response->getContent(), true);

        $this->assertEquals(
            [
                'end_date' =>
                   [
                        0 => 'The end date is not a valid date.',
                    ],
            ],
            $data
        );

        $this->get('/report/general?start_date=2017-03-03&end_date=2017-03-04');

        $data = json_decode($this->response->getContent(), true);

        $this->assertNotEquals(
           [
                'end_date' =>
                    [
                        0 => 'The end date is not a valid date.',
                    ],
            ],
            $data
        );
    }
}
