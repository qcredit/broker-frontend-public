<?php

/* application/choose-offer.twig */
class __TwigTemplate_857c9d925311cbbe969f28b8d38a6b5cb219f3f89beca1db0f7c599e32d0b703 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.twig", "application/choose-offer.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'container' => array($this, 'block_container'),
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
        echo "Confirm offer";
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "  <div class=\"container mt-5\">
    <div class=\"row\">
      <div class=\"col\">
        <p>Please make your final selections for offer #";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "id", array()), "html", null, true);
        echo "</p>
      </div>
    </div>
    <div class=\"row\">
      ";
        // line 11
        if (twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "errors", array())) {
            // line 12
            echo "        <ul>
        ";
            // line 13
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "errors", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["error"]) {
                // line 14
                echo "          <li>";
                echo twig_escape_filter($this->env, $context["error"], "html", null, true);
                echo "</li>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['error'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 16
            echo "        </ul>
      ";
        }
        // line 18
        echo "    </div>
    <div class=\"row\">
      <form method=\"POST\">
        <div class=\"form-group\">
          <label for=\"signingMethod\">Signing method:</label>
          <select id=\"signingMethod\" name=\"signingMethod\">
            <option value=\"SignOnPaper\">Sign on paper</option>
            <option value=\"CallMe\">Call me</option>
            <option value=\"SignWithBlueMedia\">Sign with blue media</option>
            <option value=\"OTP\">OTP</option>
          </select>
          ";
        // line 29
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "errors", array()), "signingMethod", array())) {
            // line 30
            echo "            <small class=\"form-text text-danger\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "errors", array()), "signingMethod", array()), "html", null, true);
            echo "</small>
          ";
        }
        // line 32
        echo "        </div>
        <div class=\"form-group\">
          <label for=\"firstPayment\">First payment date:</label>
          <input type=\"text\" name=\"firstPaymentDate\" id=\"firstPayment\" class=\"form-control\"/>
          ";
        // line 36
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "errors", array()), "firstPaymentDate", array())) {
            // line 37
            echo "            <small class=\"form-text text-danger\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "errors", array()), "firstPaymentDate", array()), "html", null, true);
            echo "</small>
          ";
        }
        // line 39
        echo "        </div>
        <div class=\"form-group\">
          <button type=\"submit\" class=\"btn btn-primary\">Let's go!</button>
        </div>
        <input type=\"hidden\" name=\"";
        // line 43
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "name", array()), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "name", array()), "html", null, true);
        echo "\">
        <input type=\"hidden\" name=\"";
        // line 44
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "value", array()), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "value", array()), "html", null, true);
        echo "\">
      </form>
    </div>
  </div>
";
    }

    public function getTemplateName()
    {
        return "application/choose-offer.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  123 => 44,  117 => 43,  111 => 39,  105 => 37,  103 => 36,  97 => 32,  91 => 30,  89 => 29,  76 => 18,  72 => 16,  63 => 14,  59 => 13,  56 => 12,  54 => 11,  47 => 7,  42 => 4,  39 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "application/choose-offer.twig", "/app/templates/application/choose-offer.twig");
    }
}
