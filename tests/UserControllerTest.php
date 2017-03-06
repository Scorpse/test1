<?php
/**
 * Created by PhpStorm.
 * User: scorpse
 * Date: 06-Mar-17
 * Time: 3:13 AM
 */

namespace App\Http\Controllers;


class UserControllerTest extends \TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserShow()
    {
        $this->get('/users/1');

        $user = json_decode($this->response->getContent(), true);

        $this->assertEquals(
            [
                'data' =>
                    [
                        'id' => 1,
                        'firstname' => 'Ova',
                        'lastname' => 'Blanda',
                        'country' => 'AL',
                        'gender' => 'M',
                        'email' => 'aokon@yahoo.com',
                    ],
            ],
            $user
        );
    }
}
