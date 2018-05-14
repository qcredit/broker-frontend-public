<?php

/* $sliders.twig */
class __TwigTemplate_a79ecb1965b5728399d0e80413a81a40be12f8aab881ca16754d92d3a4274504 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"landing-component\">
  <p class=\"range-slider-label\">";
        // line 2
        echo gettext("How much do you want to borrow?");
        echo "</p>
  <div class=\"landing-slider landing-form-amount\">
    <div class=\"landing-form-adjust landing-form-adjust-decrease\"><span>&#8722;</span></div>
    <input type=\"range\" min=\"1000\" max=\"10000\" step=\"500\" value=\"";
        // line 5
        if (twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "loanAmount", array())) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "loanAmount", array()), "html", null, true);
        } else {
            echo "2000";
        }
        echo "\" id=\"loanAmountSlider\">
    <select name=\"loanAmount\" id=\"loanAmount\" class=\"range-slider-value range-slider-select-value\">
    </select>
    <div class=\"landing-form-adjust landing-form-adjust-increase\"><span>&#43;</span></div>
  </div>
  <p class=\"range-slider-label\">";
        // line 10
        echo gettext("How fast do you want to pay off (months)?");
        echo "</p>
  <div class=\"landing-slider landing-form-duration\">
    <div class=\"landing-form-adjust landing-form-adjust-decrease\"><span>&#8722;</span></div>
    <input type=\"range\" min=\"6\" max=\"72\" step=\"3\" value=\"";
        // line 13
        if (twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "loanTerm", array())) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "loanTerm", array()), "html", null, true);
        } else {
            echo "12";
        }
        echo "\" id=\"loanTermSlider\">
    <select name=\"loanTerm\" id=\"loanTerm\" class=\"range-slider-value range-slider-select-value\">
    </select>
    <div class=\"landing-form-adjust landing-form-adjust-increase\"><span>&#43;</span></div>
  </div>
  <p class=\"range-slider-label range-slider-monhly-label\">";
        // line 18
        echo gettext("Approximate monthly installment ");
        echo "<span class=\"monthly-installment\"></span></p>
</div>
<div class=\"bouncing-loader\">
  <div class=\"bouncing-loader__round\"></div>
  <div class=\"bouncing-loader__round\"></div>
  <div class=\"bouncing-loader__round\"></div>
</div>
";
    }

    public function getTemplateName()
    {
        return "\$sliders.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  62 => 18,  50 => 13,  44 => 10,  32 => 5,  26 => 2,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "\$sliders.twig", "/app/templates/\$sliders.twig");
    }
}
