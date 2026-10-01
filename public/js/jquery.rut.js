(function($) {
  function cleanRut(value) {
    return (value || "").toString().replace(/[^0-9kK]/g, "").toUpperCase();
  }

  function formatRut(value) {
    var clean = cleanRut(value);
    if (clean.length <= 1) {
      return clean;
    }

    var body = clean.slice(0, -1);
    var dv = clean.slice(-1);
    var formatted = "";

    while (body.length > 3) {
      formatted = "." + body.slice(-3) + formatted;
      body = body.slice(0, -3);
    }

    if (body.length) {
      formatted = body + formatted;
    }

    return formatted + "-" + dv;
  }

  function validateRut(value) {
    var clean = cleanRut(value);
    if (clean.length < 2) {
      return false;
    }

    var body = clean.slice(0, -1);
    var dv = clean.slice(-1);
    var sum = 0;
    var multiplier = 2;
    var i;

    for (i = body.length - 1; i >= 0; i -= 1) {
      sum += parseInt(body.charAt(i), 10) * multiplier;
      multiplier = multiplier === 7 ? 2 : multiplier + 1;
    }

    var remainder = 11 - (sum % 11);
    var expected;

    if (remainder === 11) {
      expected = "0";
    } else if (remainder === 10) {
      expected = "K";
    } else {
      expected = String(remainder);
    }

    return expected === dv;
  }

  $.Rut = {
    validar: validateRut,
    formatear: formatRut
  };

  $.fn.Rut = function(options) {
    var settings = $.extend({
      format_on: "keyup"
    }, options);

    return this.each(function() {
      var $input = $(this);

      function applyFormat() {
        $input.val(formatRut($input.val()));
      }

      if (settings.format_on === "keyup") {
        $input.on("keyup", applyFormat);
      }

      $input.on("blur", applyFormat);
    });
  };
}(jQuery));
