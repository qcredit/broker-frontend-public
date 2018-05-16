<?php

/* application/form.twig */
class __TwigTemplate_63ad481f75194016b2a36850118d0ef5731ee72a3c15ff20b1e00ff09433118b extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.twig", "application/form.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'container' => array($this, 'block_container'),
            'endBody' => array($this, 'block_endBody'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_title($context, array $blocks = array())
    {
        echo "New application";
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "
<form method=\"POST\" class=\"loan-form ";
        // line 5
        if (twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "errors", array())) {
            echo "loan-form-errors";
        }
        echo "\">
  <div class=\"loan-form-upper\">
    ";
        // line 7
        $this->loadTemplate("\$sliders.twig", "application/form.twig", 7)->display($context);
        // line 8
        echo "  </div>
  <div class=\"step-1\">
    <div class=\"form-block form-block-general\">
      ";
        // line 11
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["fields"] ?? null), "general", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["field"]) {
            // line 12
            echo "        ";
            if ((twig_get_attribute($this->env, $this->source, $context["field"], "type", array()) === "checkbox")) {
                // line 13
                echo "          <div class=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "type", array()), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                echo "\">
            <input type=\"";
                // line 14
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "type", array()), "html", null, true);
                echo "\" id=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                echo "\" name=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                echo "\" class=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                echo "-checkbox\" ";
                if ((twig_get_attribute($this->env, $this->source, $context["field"], "name", array()) === "marketingConsent")) {
                    echo "checked";
                }
                echo ">
            <label for=\"";
                // line 15
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "label", array()), "html", null, true);
                echo "</label>
          </div>
          ";
            } elseif (((twig_get_attribute($this->env, $this->source,             // line 17
$context["field"], "name", array()) === "loanTerm") || (twig_get_attribute($this->env, $this->source, $context["field"], "name", array()) === "loanAmount"))) {
                // line 18
                echo "            ";
                // line 19
                echo "          ";
            } else {
                // line 20
                echo "            <div class=\"field ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "type", array()), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                echo " ";
                if (twig_get_attribute($this->env, $this->source, $context["field"], "required", array())) {
                    echo "required";
                }
                echo "\">
              <label for=\"\">";
                // line 21
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "label", array()), "html", null, true);
                if (twig_get_attribute($this->env, $this->source, $context["field"], "required", array())) {
                    echo "*";
                }
                echo "</label>
              <input type=\"";
                // line 22
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "type", array()), "html", null, true);
                echo "\" id=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                echo "\" name=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                echo "\" value=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "getAttribute", array(0 => twig_get_attribute($this->env, $this->source, $context["field"], "name", array())), "method"), "html", null, true);
                echo "\">
              ";
                // line 23
                if ((($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 = twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "errors", array())) && is_array($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5) || $__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 instanceof ArrayAccess ? ($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5[twig_get_attribute($this->env, $this->source, $context["field"], "name", array())] ?? null) : null)) {
                    echo "<p class=\"rules\">";
                    echo twig_escape_filter($this->env, (($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a = twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "errors", array())) && is_array($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a) || $__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a instanceof ArrayAccess ? ($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a[twig_get_attribute($this->env, $this->source, $context["field"], "name", array())] ?? null) : null), "html", null, true);
                    echo "</p>";
                }
                // line 24
                echo "            </div>
        ";
            }
            // line 26
            echo "      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['field'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 27
        echo "    </div>
    ";
        // line 28
        if ( !twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "errors", array())) {
            // line 29
            echo "    <div class=\"form-block form-block-submit\">
      <div class=\"broker-btn broker-btn-gold\">Proceed</div>
    </div>
    ";
        }
        // line 33
        echo "  </div>
  <div class=\"step-2\">
    ";
        // line 35
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["fields"] ?? null));
        foreach ($context['_seq'] as $context["sectionId"] => $context["section"]) {
            // line 36
            echo "    ";
            if ( !($context["sectionId"] === "general")) {
                // line 37
                echo "      <div class=\"form-block form-block-";
                echo twig_escape_filter($this->env, $context["sectionId"], "html", null, true);
                echo "\">
        <p>
          ";
                // line 39
                if (($context["sectionId"] === "personal")) {
                    // line 40
                    echo "            Personal information
          ";
                } elseif ((                // line 41
$context["sectionId"] === "housing")) {
                    // line 42
                    echo "            Housing information
          ";
                } elseif ((                // line 43
$context["sectionId"] === "income")) {
                    // line 44
                    echo "            Income information
          ";
                } elseif ((                // line 45
$context["sectionId"] === "account")) {
                    // line 46
                    echo "            Account information
          ";
                } elseif ((                // line 47
$context["sectionId"] === "additional")) {
                    // line 48
                    echo "            Additional information
          ";
                }
                // line 50
                echo "        </p>
        ";
                // line 51
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["section"]);
                foreach ($context['_seq'] as $context["_key"] => $context["field"]) {
                    // line 52
                    echo "          ";
                    if ((twig_get_attribute($this->env, $this->source, $context["field"], "type", array()) === "checkbox")) {
                        // line 53
                        echo "            <div class=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "type", array()), "html", null, true);
                        echo " ";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                        echo "\">
              <input type=\"";
                        // line 54
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "type", array()), "html", null, true);
                        echo "\" id=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                        echo "\" name=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                        echo "\" class=\"";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                        echo "-checkbox\" ";
                        if ((twig_get_attribute($this->env, $this->source, $context["field"], "name", array()) === "marketingConsent")) {
                            echo "checked";
                        }
                        echo ">
              <label for=\"";
                        // line 55
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                        echo "\">";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "label", array()), "html", null, true);
                        echo "</label>
            </div>
            ";
                    } elseif (((twig_get_attribute($this->env, $this->source,                     // line 57
$context["field"], "name", array()) === "loanTerm") || (twig_get_attribute($this->env, $this->source, $context["field"], "name", array()) === "loanAmount"))) {
                        // line 58
                        echo "              ";
                        // line 59
                        echo "            ";
                    } else {
                        // line 60
                        echo "              <div class=\"field ";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "type", array()), "html", null, true);
                        echo " ";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                        echo " ";
                        if (twig_get_attribute($this->env, $this->source, $context["field"], "required", array())) {
                            echo "required";
                        }
                        echo "\">
                <label for=\"\">";
                        // line 61
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "label", array()), "html", null, true);
                        if (twig_get_attribute($this->env, $this->source, $context["field"], "required", array())) {
                            echo "*";
                        }
                        echo "</label>
                ";
                        // line 62
                        if ((twig_get_attribute($this->env, $this->source, $context["field"], "type", array()) === "select")) {
                            // line 63
                            echo "                  <select id=\"";
                            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                            echo "\" name=\"";
                            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                            echo "\" value=\"";
                            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "getAttribute", array(0 => twig_get_attribute($this->env, $this->source, $context["field"], "name", array())), "method"), "html", null, true);
                            echo "\">
                    <option></option>
                    ";
                            // line 65
                            $context['_parent'] = $context;
                            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["field"], "enum", array()));
                            foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
                                // line 66
                                echo "                    <option value=\"";
                                echo twig_escape_filter($this->env, $context["option"], "html", null, true);
                                echo "\" ";
                                if ((twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "getAttribute", array(0 => twig_get_attribute($this->env, $this->source, $context["field"], "name", array())), "method") == $context["option"])) {
                                    echo "selected=\"true\"";
                                }
                                echo ">";
                                echo twig_escape_filter($this->env, $context["option"], "html", null, true);
                                echo "</option>
                    ";
                            }
                            $_parent = $context['_parent'];
                            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
                            $context = array_intersect_key($context, $_parent) + $_parent;
                            // line 68
                            echo "                  </select>
                  ";
                        } else {
                            // line 70
                            echo "                  <input type=\"";
                            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "type", array()), "html", null, true);
                            echo "\" id=\"";
                            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                            echo "\" name=\"";
                            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "name", array()), "html", null, true);
                            echo "\" value=\"";
                            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "getAttribute", array(0 => twig_get_attribute($this->env, $this->source, $context["field"], "name", array())), "method"), "html", null, true);
                            echo "\">
                ";
                        }
                        // line 72
                        echo "                ";
                        if ((($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 = twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "errors", array())) && is_array($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57) || $__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 instanceof ArrayAccess ? ($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57[twig_get_attribute($this->env, $this->source, $context["field"], "name", array())] ?? null) : null)) {
                            echo "<p class=\"rules\">";
                            echo twig_escape_filter($this->env, (($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 = twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "errors", array())) && is_array($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9) || $__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 instanceof ArrayAccess ? ($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9[twig_get_attribute($this->env, $this->source, $context["field"], "name", array())] ?? null) : null), "html", null, true);
                            echo "</p>";
                        }
                        // line 73
                        echo "              </div>
          ";
                    }
                    // line 75
                    echo "        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['field'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 76
                echo "        </div>
      ";
            }
            // line 78
            echo "    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['sectionId'], $context['section'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 79
        echo "    <div class=\"form-block form-block-submit\">
      <button type=\"submit\" class=\"broker-btn broker-btn-gold broker-btn-send\">Submit form</button>
    </div>
  </div>
  <!--";
        // line 83
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "errors", array()), "payoutMethod", array())) {
            echo "<p class=\"rules\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "errors", array()), "payoutMethod", array()), "html", null, true);
            echo "</p>";
        }
        echo " -->
    <input type=\"hidden\" name=\"";
        // line 84
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "name", array()), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "name", array()), "html", null, true);
        echo "\">
    <input type=\"hidden\" name=\"";
        // line 85
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "value", array()), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "value", array()), "html", null, true);
        echo "\">
  <input type=\"hidden\" name=\"applicationHash\" id=\"applicationHash\" value=\"";
        // line 86
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "applicationHash", array()), "html", null, true);
        echo "\">
</form>
";
    }

    // line 90
    public function block_endBody($context, array $blocks = array())
    {
        // line 91
        echo "  <script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/ajv/6.4.0/ajv.min.js\"></script>
  <script type=\"text/javascript\" src=\"/src/scripts/ajv.broker.js\"></script>
  <script type=\"text/javascript\">
    var ajv = new Ajv({ allErrors: true, verbose: true, coerceTypes: true });
    ajv.addKeyword('documentNr', {
      validate: function documentNr(schema,data)
      {
        documentNr.errors = [];
        if (!validatePolandDocument(data))
        {
          documentNr.errors.push({
            \"keyword\": \"documentNr\",
            \"dataPath\": \".documentNr\",
            \"params\": {},
            \"message\": \"Invalid format provided.\"
          });

          return false;
        }

        return true;
      },
      errors: true
    });

    var schema = '';
    fetch('/application/schema')
      .then(function(response) {
        return response.json();
      }).then(function (json) { schema = json; });

    \$('button[type=\"submit\"]').click(function(e) {
      var valid = ajv.validate(schema, getFormData());

      if (!valid) {
        e.preventDefault();
        localize_en(ajv.errors);
        console.log(ajv.errors);
        console.log(ajv.errorsText(ajv.errors, { separator: '\\n'}));
        var error_list = ajv.errors;
        for(var i = 0; i < error_list.length; i++) {
          var err_target = \$('.field'+error_list[i].dataPath);
          var err_msg = error_list[i].message;
          if(err_msg){
            if(!err_target.find('.rules').length){
              err_target.addClass('error');
              err_target.append('<p class=\"rules\">'+err_msg+'</p>');
            } else {
              err_target.find('.rules').text(err_msg);
            }
          }
        }
      }
    });

    \$('input').on('change', function(e) {
      var attrId = \$(this)[0].id;
      var formValues = getFormData();
      var parent = \$(this).parent();
      runSchemaLive(attrId, formValues, parent);
    });
    \$('select').on('change', function(e) {
      var attrId = \$(this)[0].id;
      var formValues = getFormData();
      var parent = \$(this).parent();
      runSchemaLive(attrId, formValues, parent);
    });

    \$('.step-1 .broker-btn').on('click', function() {
      var formValues = getFormData();
      var errors_list = []
      \$('.step-1').find('div.field').each(function(){
        var parent = \$(this);
        var attrId = parent.find('input').attr('id');
        var noerrors = runSchemaLive(attrId, formValues, parent);
        return errors_list.push(noerrors);
      });
      if(errors_list[0] || errors_list[1] || errors_list[2] ) { // IF one of the three is validated
        // Show rest of the form & save data
        \$('.step-1 .broker-btn').css('display','none');
        \$('.step-2').css('display','block');
        sendpreData(formValues);
      } else if(!errors_list[0] && !errors_list[1] && !errors_list[2]) {  // IF all are empty
        // show form but don't send data yet
        \$('.step-1 .broker-btn').css('display','none');
        \$('.step-2').css('display','block');
      }
      console.log(\"error status: \" + errors_list)
    });

    function runSchemaLive(attrId,formValues,parent) {
      ajv.validate(schema, formValues);
      localize_en(ajv.errors);
      var err_obj = searchError(attrId, ajv.errors);
      if(err_obj) {
        parent.addClass('error');
        if(!parent.find('.rules').length){
          parent.append('<p class=\"rules\">'+err_obj.message+'</p>');
          console.log(err_obj);
        } else {
          parent.find('.rules').text(err_obj.message);
        }
        return false;
      } else {
        parent.removeClass('error');
        parent.find('p.rules').text('');
        return true;
      }
    }

    function sendpreData(values) {
      values['";
        // line 202
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "name", array()), "html", null, true);
        echo "'] = '";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "name", array()), "html", null, true);
        echo "';
      values['";
        // line 203
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "value", array()), "html", null, true);
        echo "'] = '";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "value", array()), "html", null, true);
        echo "';
      \$.ajax({
        method: \"POST\",
        url: \"/application\",
        data: values
      })
        .done(function( data ) {
          \$('input#applicationHash').val(data.applicationHash);
        });
    }
  </script>
";
    }

    public function getTemplateName()
    {
        return "application/form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  492 => 203,  486 => 202,  373 => 91,  370 => 90,  363 => 86,  357 => 85,  351 => 84,  343 => 83,  337 => 79,  331 => 78,  327 => 76,  321 => 75,  317 => 73,  310 => 72,  298 => 70,  294 => 68,  279 => 66,  275 => 65,  265 => 63,  263 => 62,  256 => 61,  245 => 60,  242 => 59,  240 => 58,  238 => 57,  231 => 55,  217 => 54,  210 => 53,  207 => 52,  203 => 51,  200 => 50,  196 => 48,  194 => 47,  191 => 46,  189 => 45,  186 => 44,  184 => 43,  181 => 42,  179 => 41,  176 => 40,  174 => 39,  168 => 37,  165 => 36,  161 => 35,  157 => 33,  151 => 29,  149 => 28,  146 => 27,  140 => 26,  136 => 24,  130 => 23,  120 => 22,  113 => 21,  102 => 20,  99 => 19,  97 => 18,  95 => 17,  88 => 15,  74 => 14,  67 => 13,  64 => 12,  60 => 11,  55 => 8,  53 => 7,  46 => 5,  43 => 4,  40 => 3,  34 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "application/form.twig", "/app/templates/application/form.twig");
    }
}
