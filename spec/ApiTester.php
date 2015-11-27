<?php namespace spec\Pisa\GizmoAPI;

use Faker\Factory as Faker;
use PhpSpec\ObjectBehavior;

class ApiTester extends ObjectBehavior
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    public function fakeHost(array $fields = [])
    {
        return array_merge([
            'IsLocked'           => $this->faker->boolean(),
            'IsSecurityEnabled'  => $this->faker->boolean(),
            'OsInfo'             => [
                'Version'                => $this->faker->randomDigit(),
                'Is64BitOperatingSystem' => $this->faker->boolean(),
                'Is64BitProcess'         => $this->faker->boolean(),
            ],
            'IsOutOfOrder'       => $this->faker->boolean(),
            'State'              => $this->faker->randomDigit(),
            'IsMaintenanceMode'  => $this->faker->boolean(),
            'Moduile'            => [
                'ModuleType'    => $this->faker->randomDigit(),
                'ModuleVersion' => $this->faker->optional()->ipv4(),
                'FileName'      => $this->faker->optional()->fileExtension(),
            ],
            'HostName'           => $this->faker->username(),
            'IpAddress'          => $this->faker->optional()->localIpv4(),
            'Port'               => $this->faker->numberBetween(1024, 65535),
            'MacAddress'         => $this->faker->macAddress(),
            'Registered'         => $this->faker->boolean(),
            'Number'             => $this->faker->numberBetween(1, 100),
            'HasValidDispatcher' => $this->faker->boolean(),
            'GroupId'            => $this->faker->numberBetween(1, 10),
            'Id'                 => $this->faker->numberBetween(1, 100),
        ], $fields);
    }

    public function fakeUser(array $fields = [])
    {
        return array_merge([
            'Id'          => $this->faker->randomDigitNotNull(),
            'UserName'    => $this->faker->username(),
            'FirstName'   => $this->faker->firstName(),
            'LastName'    => $this->faker->lastName(),
            'Email'       => $this->faker->email(),
            'BirthDate'   => $this->faker->iso8601(),
            'City'        => $this->faker->city(),
            'Address'     => $this->faker->streetAddress(),
            'PostCode'    => $this->faker->postcode(),
            'Country'     => $this->faker->country(),
            'Phone'       => $this->faker->phoneNumber(),
            'MobilePhone' => $this->faker->phoneNumber(),
            'Sex'         => $this->faker->numberBetween(1, 2),
            'Role'        => 1,
            'IsEnabled'   => $this->faker->boolean(),
            'GroupID'     => $this->faker->randomDigitNotNull(),
        ], $fields);
    }

}
