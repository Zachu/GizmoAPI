<?php namespace spec\Pisa\GizmoAPI;

use Faker\Factory as Faker;
use GuzzleHttp\Psr7\Response as HttpResponse;
use Pisa\GizmoAPI\Adapters\GuzzleResponseAdapter as HttpResponseAdapter;

class Helper
{
    public static function fakeHost(array $fields = [])
    {
        $faker = Faker::create();
        return array_merge([
            'IsLocked'           => $faker->boolean(),
            'IsSecurityEnabled'  => $faker->boolean(),
            'OsInfo'             => [
                'Version'                => $faker->randomDigit(),
                'Is64BitOperatingSystem' => $faker->boolean(),
                'Is64BitProcess'         => $faker->boolean(),
            ],
            'IsOutOfOrder'       => $faker->boolean(),
            'State'              => $faker->randomDigit(),
            'IsMaintenanceMode'  => $faker->boolean(),
            'Moduile'            => [
                'ModuleType'    => $faker->randomDigit(),
                'ModuleVersion' => $faker->optional()->ipv4(),
                'FileName'      => $faker->optional()->fileExtension(),
            ],
            'HostName'           => $faker->username(),
            'IpAddress'          => $faker->optional()->localIpv4(),
            'Port'               => $faker->numberBetween(1024, 65535),
            'MacAddress'         => $faker->macAddress(),
            'Registered'         => $faker->boolean(),
            'Number'             => $faker->numberBetween(1, 100),
            'HasValidDispatcher' => $faker->boolean(),
            'GroupId'            => $faker->numberBetween(1, 10),
            'Id'                 => $faker->numberBetween(1, 100),
        ], $fields);
    }

    public static function fakeUser(array $fields = [])
    {
        $faker = Faker::create();
        return array_merge([
            'Id'          => $faker->randomDigitNotNull(),
            'UserName'    => $faker->username(),
            'FirstName'   => $faker->firstName(),
            'LastName'    => $faker->lastName(),
            'Email'       => $faker->email(),
            'BirthDate'   => $faker->iso8601(),
            'City'        => $faker->city(),
            'Address'     => $faker->streetAddress(),
            'PostCode'    => $faker->postcode(),
            'Country'     => $faker->country(),
            'Phone'       => $faker->phoneNumber(),
            'MobilePhone' => $faker->phoneNumber(),
            'Sex'         => $faker->numberBetween(1, 2),
            'Role'        => 1,
            'IsEnabled'   => $faker->boolean(),
            'GroupID'     => $faker->randomDigitNotNull(),
            'Registered'  => $faker->iso8601(),
        ], $fields);
    }

    public static function fakeNews(array $fields = [])
    {
        $faker = Faker::create();
        return array_merge([
            'Data'      => $faker->paragraph(),
            'Title'     => $faker->sentence(),
            'Date'      => $faker->iso8601(),
            'StartDate' => $faker->iso8601(),
            'EndDate'   => $faker->iso8601(),
            'Url'       => $faker->url(),
            'Id'        => $faker->randomDigitNotNull(),
        ], $fields);
    }

    public static function fakeSession(array $fields = [])
    {
        $faker = Faker::create();
        return array_merge([
            'Id'               => $faker->randomDigitNotNull(),
            'UserId'           => $faker->randomDigitNotNull(),
            'HostId'           => $faker->randomDigitNotNull(),
            'CreationTime'     => $faker->iso8601(),
            'DestructionTime'  => $faker->iso8601(),
            'Span'             => $faker->time(),
            'SpanFromCreation' => $faker->time(),
            'State'            => $faker->randomDigitNotNull(),
            'IsActive'         => $faker->boolean(),
            'IsPending'        => $faker->boolean(),
            'IsPaused'         => $faker->boolean(),
            'PendTime'         => $faker->iso8601(),
            'SpanFromPend'     => $faker->time(),
        ], $fields);
    }

    public static function noContentResponse()
    {
        return new HttpResponseAdapter(new HttpResponse(204));
    }

    public static function emptyArrayResponse()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode([])
        ));
    }

    public static function randomArrayResponse()
    {
        $arr = [];
        $len = rand(1, 10);
        for ($i = 0; $i < $len; $i++) {
            $key       = str_shuffle(uniqid());
            $arr[$key] = md5($key);
        }

        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode($arr)
        ));
    }

    public static function randomStringResponse()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(str_shuffle(uniqid()))
        ));
    }

    public static function contentResponse($content)
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode($content)
        ));
    }

    public static function falseResponse()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(false)
        ));
    }

    public static function nullResponse()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(null)
        ));
    }

    public static function trueResponse()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(true)
        ));
    }

    public static function zeroResponse()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(0)
        ));
    }

    public static function oneResponse()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(1)
        ));
    }

    public static function timeResponse()
    {
        return new HttpResponseAdapter(new HttpResponse(
            200,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(date('c'))
        ));
    }

    public static function internalServerErrorResponse()
    {
        return new HttpResponseAdapter(new HttpResponse(
            500,
            ['Content-Type' => 'application/json;charset=utf-8'],
            json_encode(['message' => 'An error has occured'])
        ));
    }
}
