<?php

namespace peertxt\Observers;

class ContactObserver
{
    public function saving($model)
    {
        $phone = str_replace('-', '', filter_var($model->phone, FILTER_SANITIZE_NUMBER_INT));
        $stripped_phone = str_replace("+1", "", $phone);
        $stripped_phone = str_replace("+", "", $stripped_phone);
        $stripped_phone = ltrim($stripped_phone, '0');
        $stripped_phone = ltrim($stripped_phone, '1');
        $stripped_phone = trim(preg_replace("/[^0-9]/", "", $stripped_phone));

        $model->phone = $stripped_phone;
    }
}
