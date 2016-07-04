<?php namespace spec\Pisa\GizmoAPI\Models;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use PhpSpec\ObjectBehavior;
use Pisa\GizmoAPI\Contracts\HttpClient;
use Pisa\GizmoAPI\Models\BaseModel;

class BaseModelSpec extends ObjectBehavior
{
    public function let(HttpClient $client, Factory $factory)
    {
        $this->beAnInstanceOf('spec\Pisa\GizmoAPI\Models\ConcreteModel');
        $this->beConstructedWith($client, $factory, ['Id' => 1]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('spec\Pisa\GizmoAPI\Models\ConcreteModel');
    }

    //
    // Validator
    //

    public function it_should_set_rules()
    {
        $rules = [
            'name' => 'required',
        ];

        $this->setRules($rules);
        $this->getRules()->shouldBe($rules);
    }

    public function it_should_validate_by_rules(Factory $factory, Validator $validator)
    {
        $rules = [
            'name' => 'required',
        ];
        $this->setRules($rules);

        // Invalid
        $factory->make($this->getAttributes(), $rules)->shouldBeCalled()->willReturn($validator);
        $validator->fails()->shouldBeCalled()->willReturn(true);
        $this->isValid()->shouldBe(false);

        // Valid
        $this->name = 'Foo';
        $factory->make($this->getAttributes(), $rules)->shouldBeCalled()->willReturn($validator);
        $validator->fails()->shouldBeCalled()->willReturn(false);
        $this->isValid()->shouldBe(true);
    }
}

class ConcreteModel extends BaseModel
{
    public function create()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
    }
}
