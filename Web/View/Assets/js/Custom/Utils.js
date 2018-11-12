function validate(formId, thisValue) {
    let form = document.getElementById(formId);

    if(form!=null) {
        let event = function (event) {
            event.preventDefault();
            let id = event.target.id;
            let name = id.indexOf("Form");
            name = id.substr(0, name);

            let inputs = document.getElementsByClassName(name + "Input");
            let result = new Array();
            let errorContainer = document.getElementsByClassName("validationError");

            for (let i = 0; i < inputs.length; i++) {
                errorContainer[i].innerHTML = "";
                inputs[i].classList.remove("inputError");

                result.push({
                    id: inputs[i].getAttribute("id"),
                    required: inputs[i].getAttribute("data-required"),
                    value: inputs[i].value,
                    errorContainer: errorContainer[i]
                });
            }

            let errors = result.filter(function (val, i, a) {
                return (val.required == "true") ? document.getElementById(val.id).value == '' : false;
            });

            if (errors.length == 0) {
                document.getElementById(id).submit();
            }

            errors.forEach(function (value, index, array) {
                let e = document.getElementById(value.id);
                e.classList.add("inputError");
                value.errorContainer.innerHTML = "Campo Obrigatório";

            });
        };

        validationFormSuccess = validationFormSuccess.bind(thisValue);

        event = event.bind(validationFormSuccess);

        form.onsubmit = event;
    }
}

function goto(loc)
{
    location.href = loc;
}