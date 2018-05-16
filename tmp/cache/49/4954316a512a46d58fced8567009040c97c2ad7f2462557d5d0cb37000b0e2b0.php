<?php

/* admin/application.twig */
class __TwigTemplate_342a810e7bf8977e62bd96e58520f8ae0c3a23e61a7ea5975bd4113b4b1ad303 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "admin/application.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'container' => array($this, 'block_container'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "admin.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_title($context, array $blocks = array())
    {
        echo "Application ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "id", array()), "html", null, true);
        echo " details";
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "  ";
        if (($context["flash"] ?? null)) {
            // line 5
            echo "      <div class=\"row\">
          ";
            // line 6
            if (twig_get_attribute($this->env, $this->source, ($context["flash"] ?? null), "success", array())) {
                // line 7
                echo "              <div class=\"alert alert-success\" role=\"alert\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["flash"] ?? null), "success", array()), "html", null, true);
                echo "</div>
          ";
            } elseif (twig_get_attribute($this->env, $this->source,             // line 8
($context["flash"] ?? null), "error", array())) {
                // line 9
                echo "              <div class=\"alert alert-danger\" role=\"alert\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["flash"] ?? null), "error", array()), "html", null, true);
                echo "</div>
          ";
            }
            // line 11
            echo "      </div>
  ";
        }
        // line 13
        echo "  <div class=\"row\">
      <div class=\"col col-sm-12 col-md-6 col-lg-4 application-loan-info\">
          <h5>Loan information</h5>
          <table class=\"application-information-table\">
            <tbody>
              <tr>
                <td class=\"key\">Amount: </td>
                <td class=\"value\">";
        // line 20
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "loanAmount", array()), "html", null, true);
        echo "</td>
              </tr>
              <tr>
                <td class=\"key\">Duration: </td>
                <td class=\"value\">";
        // line 24
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "loanTerm", array()), "html", null, true);
        echo "</td>
              </tr>
            </tbody>
          </table>
      </div>
      <div class=\"col col-sm-12 col-md-6 col-lg-6 application-loan-accepted-table\">
          <h5>Signed loans</h5>
          <table class=\"table\">
              <thead>
                  <tr>
                      <th>Lender Id</th>
                      <th>Lender Name</th>
                      <th>Amount</th>
                      <th>Status</th>
                  </tr>
              </thead>
              <tbody>
                ";
        // line 41
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "offers", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["offer"]) {
            // line 42
            echo "                  ";
            if (twig_get_attribute($this->env, $this->source, $context["offer"], "isAccepted", array())) {
                // line 43
                echo "                    <tr>
                        <td>";
                // line 44
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["offer"], "partner", array()), "id", array()), "html", null, true);
                echo "</td>
                        <td>";
                // line 45
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["offer"], "partner", array()), "name", array()), "html", null, true);
                echo "</td>
                        <td>";
                // line 46
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "loanAmount", array()), "html", null, true);
                echo " PLN</td>
                        <td class=\"status-accepted\">Accepted</td>
                    </tr>
                  ";
            }
            // line 50
            echo "                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['offer'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 51
        echo "              </tbody>
            </table>
      </div>
  </div>
  <div class=\"row\">
      <div class=\"col-sm-12\">
          <h5>Partners' offers</h5>
          ";
        // line 58
        if (twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "offers", array())) {
            // line 59
            echo "          <table class=\"table\">
              <thead>
                  <tr>
                      <th>Lender Id</th>
                      <th>Lender Name</th>
                      <th>Amount</th>
                      <th>Remote Id</th>
                      <th>Status</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  ";
            // line 71
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "offers", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["offer"]) {
                // line 72
                echo "                      <tr>
                          <td>";
                // line 73
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["offer"], "partner", array()), "id", array()), "html", null, true);
                echo "</td>
                          <td>";
                // line 74
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["offer"], "partner", array()), "name", array()), "html", null, true);
                echo "</td>
                          <td>";
                // line 75
                if (twig_get_attribute($this->env, $this->source, $context["offer"], "loanAmount", array())) {
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "loanAmount", array()), "html", null, true);
                    echo " PLN";
                }
                echo "</td>
                          <td>";
                // line 76
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "remoteId", array()), "html", null, true);
                echo "</td>
                          <td class=\"";
                // line 77
                if (twig_get_attribute($this->env, $this->source, $context["offer"], "isAccepted", array())) {
                    echo "status-accepted";
                } elseif (twig_get_attribute($this->env, $this->source, $context["offer"], "isInProcess", array())) {
                    echo "status-in-process";
                } elseif (twig_get_attribute($this->env, $this->source, $context["offer"], "isPaidOut", array())) {
                    echo "status-paidout";
                } else {
                    echo "status-rejected";
                }
                echo "\">";
                if (twig_get_attribute($this->env, $this->source, $context["offer"], "isAccepted", array())) {
                    echo "Accepted";
                } elseif (twig_get_attribute($this->env, $this->source, $context["offer"], "isInProcess", array())) {
                    echo "In process";
                } elseif (twig_get_attribute($this->env, $this->source, $context["offer"], "isPaidOut", array())) {
                    echo "Paid out";
                } else {
                    echo "Rejected";
                }
                echo "</td>
                          <td><a href=\"/admin/offers/update/";
                // line 78
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "id", array()), "html", null, true);
                echo "\"><span class=\"text-uppercase\">update</span></a></td>
                      </tr>
                  ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['offer'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 81
            echo "              </tbody>
          </table>
          ";
        }
        // line 84
        echo "      </div>
  </div>
  <div class=\"row application-information-table-container\">
    <div class=\"col-sm-12\">
      <h5>Loan applicant information</h5>
      <div class=\"row\">
        <div class=\"col-sm-12 col-md-6 col-lg-4\">
          <table class=\"application-information-table\">
            <thead>
              <tr>
                <th>Personal</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class=\"key\">Name: </td>
                <td class=\"value\">";
        // line 100
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "firstName", array()), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "lastName", array()), "html", null, true);
        echo "</td>
              </tr>
              <tr>
                <td class=\"key\">Id:</td>
                <td class=\"value\">";
        // line 104
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "pin", array()), "html", null, true);
        echo "</td>
              </tr>
              <tr>
                <td class=\"key\">Phone: </td>
                <td class=\"value\">";
        // line 108
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "phone", array()), "html", null, true);
        echo "</td>
              </tr>
              <tr>
                <td class=\"key\">E-mail:</td>
                <td class=\"value\">";
        // line 112
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "email", array()), "html", null, true);
        echo "</td>
              </tr>
              ";
        // line 114
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "idCardNumber", array())) {
            // line 115
            echo "              <tr>
                <td class=\"key\">ID card:</td>
                <td class=\"value\">";
            // line 117
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "documentNr", array()), "html", null, true);
            echo "</td>
              </tr>
              ";
        }
        // line 120
        echo "              <tr>
                <td class=\"key\">Account num:</td>
                <td class=\"value account-number-value\">";
        // line 122
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "accountNr", array()), "html", null, true);
        echo "</td>
              </tr>
              <tr>
                <td class=\"key\">Marital status:</td>
                <td class=\"value\">";
        // line 126
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "maritalStatusType", array()), "html", null, true);
        echo "</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class=\"col-sm-12 col-md-6 col-lg-4\">
          <table class=\"application-information-table\">
            <thead>
              <tr>
                <th>Housing</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class=\"key\">Address: </td>
                <td class=\"value\">";
        // line 141
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "street", array()), "html", null, true);
        echo ",
                                  ";
        // line 142
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "houseNr", array()), "html", null, true);
        echo "
                                  ";
        // line 143
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "apartmentNr", array())) {
            echo " - ";
        }
        // line 144
        echo "                                  ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "apartmentNr", array()), "html", null, true);
        echo ",
                                  ";
        // line 145
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "city", array()), "html", null, true);
        echo "</td>
              </tr>
              <tr>
                <td class=\"key\">Residential type:</td>
                <td class=\"value\">";
        // line 149
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "residentialType", array()), "html", null, true);
        echo "</td>
              </tr>
              <tr>
                <td class=\"key\">Residence: </td>
                <td class=\"value\">";
        // line 153
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "propertyType", array()), "html", null, true);
        echo "</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class=\"col-sm-12 col-md-6 col-lg-4\">
          <table class=\"application-information-table\">
            <thead>
              <tr>
                <th colspan=\"2\">Employment & Education</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class=\"key\">Education: </td>
                <td class=\"value\">";
        // line 168
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "educationType", array()), "html", null, true);
        echo "</td>
              </tr>
              <tr>
                <td class=\"key\">Occupation type:</td>
                <td class=\"value\">";
        // line 172
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "trade", array()), "html", null, true);
        echo "</td>
              </tr>
              ";
        // line 174
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "currentStudy", array())) {
            // line 175
            echo "              <tr>
                <td class=\"key\">School:</td>
                <td class=\"value\">";
            // line 177
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "currentStudy", array()), "html", null, true);
            echo "</td>
              </tr>
              ";
        }
        // line 180
        echo "              ";
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "incomeSourceType", array())) {
            // line 181
            echo "              <tr>
                <td class=\"key\">Income type</td>
                <td class=\"value\">";
            // line 183
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "incomeSourceType", array()), "html", null, true);
            echo "</td>
              </tr>
              <tr>
                <td class=\"key\">Position: </td>
                <td class=\"value\">";
            // line 187
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "position", array()), "html", null, true);
            echo "</td>
              </tr>
              <tr>
                <td class=\"key\">Employer: </td>
                <td class=\"value\">";
            // line 191
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "employerName", array()), "html", null, true);
            echo "</td>
              </tr>
              <tr>
                <td class=\"key\">Work start:</td>
                <td class=\"value\">";
            // line 195
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "monthSince", array()), "html", null, true);
            echo ".";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "yearSince", array()), "html", null, true);
            echo "</td>
              </tr>
              ";
        }
        // line 198
        echo "              <tr>
                <td class=\"key\">Net income:</td>
                <td class=\"value\">";
        // line 200
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "data", array()), "netPerMonth", array()), "html", null, true);
        echo " PLN</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
";
    }

    public function getTemplateName()
    {
        return "admin/application.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  423 => 200,  419 => 198,  411 => 195,  404 => 191,  397 => 187,  390 => 183,  386 => 181,  383 => 180,  377 => 177,  373 => 175,  371 => 174,  366 => 172,  359 => 168,  341 => 153,  334 => 149,  327 => 145,  322 => 144,  318 => 143,  314 => 142,  310 => 141,  292 => 126,  285 => 122,  281 => 120,  275 => 117,  271 => 115,  269 => 114,  264 => 112,  257 => 108,  250 => 104,  241 => 100,  223 => 84,  218 => 81,  209 => 78,  187 => 77,  183 => 76,  176 => 75,  172 => 74,  168 => 73,  165 => 72,  161 => 71,  147 => 59,  145 => 58,  136 => 51,  130 => 50,  123 => 46,  119 => 45,  115 => 44,  112 => 43,  109 => 42,  105 => 41,  85 => 24,  78 => 20,  69 => 13,  65 => 11,  59 => 9,  57 => 8,  52 => 7,  50 => 6,  47 => 5,  44 => 4,  41 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "admin/application.twig", "/app/templates/admin/application.twig");
    }
}
