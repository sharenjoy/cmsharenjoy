<?php namespace Sharenjoy\Cmsharenjoy\Service\Formaker\Forms;

use Sharenjoy\Customer\Models\Customer;
use Theme;

class CustomerForm extends FormAbstract implements FormInterface {

    public function make(array $data)
    {
        $data['class'] .= ' typeahead';
        $data['data-remote'] = route('customerFilter')."?query=%QUERY";
        $data['data-template'] = "<div class='thumb-entry'><span class='image'><img src='{{img}}' width=40 height=40 /></span><span class='text'><strong>{{value}} {{sn}}</strong><em>{{text}}</em></span></div>";

        $value = $data['value'];
        $buttonStatus = 'none';
        $popoverTitle = '';
        $popoverContent = '';

        if ($value)
        {
            $customer = Customer::find($value);
            $data['value'] = $customer->name;
            $buttonStatus = 'block';
            $popoverTitle = $customer->name.' '.$customer->sn;
            $popoverContent = $customer->name;
        }

        $data['name'] = 'customer_name';

        $attributes = $this->attributes($data);
        
        $form = '<div class="customer-field"><div class="input-group"><div class="input-group-addon"><i class="entypo-search fa-lg"></i></div><input type="text"'.$attributes.'><input type="hidden" id="customer_id" name="customer_id" value="'.$value.'"></div><button type="button" class="btn btn-white" id="popoverCustomer" style="display: '.$buttonStatus.';">'.pick_trans('buttons.preview').'</button></div>';

        $form .= '<div id="popoverCustomerHiddenTitle" style="display: none;">'.$popoverTitle.'</div>
                  <div id="popoverCustomerHiddenContent" style="display: none;">'.$popoverContent.'</div>';

        Theme::asset()->add('typeahead', 'packages/sharenjoy/cmsharenjoy/js/typeahead.min.js');

        Theme::asset()->writeScript('script', '
            $(function() {

                $(".typeahead")
                // .on("typeahead:initialized", function($e) {
                //     console.log("initialized");
                // })
                // .on("typeahead:opened", function ($e) {
                //     console.log("opened");
                // })
                // .on("typeahead:closed", function($e) {
                //     console.log("closed");
                // })
                .on("typeahead:selected", function ($e, datum) {
                    // console.log("autocompleted");
                    // console.log(datum);
                    $("#popoverCustomerHiddenTitle").html(datum.value+" "+datum.sn);
                    $("#popoverCustomerHiddenContent").html(datum.value);
                    $(".customer-field button").css("display", "block");
                    $("#customer_id").val(datum.id);
                });
                
                $(".customer-field input[type=text]").on("keyup", function(e) {
                    if ($(this).val() == "") {
                        $("#customer_id").val("");
                    }
                });

                $("#popoverCustomer").popover({
                    html: true,
                    trigger: "hover",
                    placement: "auto",
                    container: "body",
                    content: function() {
                      return $("#popoverCustomerHiddenContent").html();
                    },
                    title: function() {
                      return $("#popoverCustomerHiddenTitle").html();
                    }
                });
            });
        ');

        Theme::asset()->writeStyle('style', '
            .customer-field button { 
                position: absolute;
                right: 15px;
                height: 36px;
            }
        ');
        
        return $form;
    }

}
