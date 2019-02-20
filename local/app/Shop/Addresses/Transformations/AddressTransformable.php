<?php

namespace App\Shop\Addresses\Transformations;

use App\Shop\Addresses\Address;
use App\Shop\Customers\Customer;
use App\Shop\Customers\Repositories\CustomerRepository;

trait AddressTransformable
{
    /**
     * Transform the address
     *
     * @param Address $address
     * @return Address
     */
    public function transformAddress(Address $address)
    {
        $obj = new Address;
        $obj->id = $address->id;
        $obj->alias = $address->alias;
        $obj->address_1 = $address->address_1;
        $obj->address_2 = $address->address_2;
        $obj->zip = $address->zip;

        $obj->city = $address->city_id;

        $obj->province = $address->province_id;

        $obj->country = $address->country_id;

        $customerRepo = new CustomerRepository(new Customer);
        $customer = $customerRepo->findCustomerById($address->customer_id);
        $obj->customer = $customer->name;
        $obj->status = $address->status;

        return $obj;
    }
}
