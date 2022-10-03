<h3>Нова заявка на сайту EECU</h3>
@if(isset($fields['form_name']))
    <strong>Ім'я:</strong> {{$fields['form_name']}}<br/>
@endif
@if(isset($fields['form_phone']))
    <strong>Телефон:</strong> {{$fields['form_phone']}}<br/>
@endif
@if(isset($fields['form_email']))
    <strong>Email:</strong> {{$fields['form_email']}}<br/>
@endif
@if(isset($fields['form_message']))
    <strong>Повідомлення:</strong> {{$fields['form_message']}}<br/>
@endif