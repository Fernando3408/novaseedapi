(function(factory) {
  if (typeof define === "function" && define.amd) {
    define(["jquery", "jquery.validate"], factory);
  } else if (typeof module === "object" && module.exports) {
    module.exports = factory(require("jquery"));
  } else {
    factory(jQuery);
  }
}(function($) {
  if (!$.validator) {
    return;
  }

  $.extend($.validator.messages, {
    required: "Este campo es obligatorio.",
    remote: "Por favor, corrige este campo.",
    email: "Por favor, escribe un correo valido.",
    url: "Por favor, escribe una URL valida.",
    date: "Por favor, escribe una fecha valida.",
    dateISO: "Por favor, escribe una fecha valida (ISO).",
    number: "Por favor, escribe un numero valido.",
    digits: "Por favor, escribe solo digitos.",
    creditcard: "Por favor, escribe un numero de tarjeta valido.",
    equalTo: "Por favor, escribe el mismo valor de nuevo.",
    extension: "Por favor, escribe un valor con una extension aceptada.",
    maxlength: $.validator.format("Por favor, no escribas mas de {0} caracteres."),
    minlength: $.validator.format("Por favor, no escribas menos de {0} caracteres."),
    rangelength: $.validator.format("Por favor, escribe un valor entre {0} y {1} caracteres."),
    range: $.validator.format("Por favor, escribe un valor entre {0} y {1}."),
    max: $.validator.format("Por favor, escribe un valor menor o igual a {0}."),
    min: $.validator.format("Por favor, escribe un valor mayor o igual a {0}.")
  });
}));
