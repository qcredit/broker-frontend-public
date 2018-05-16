<?php

/* sms/offer-reminder.twig */
class __TwigTemplate_54a578ed9613ca80d8441e8956540d2bf5a92e1e5cb11defb1f7a0deb16b1c05 extends Twig_Template
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
        echo "Hey ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "firstName", array()), "html", null, true);
        echo ", you haven't made up your mind!";
    }

    public function getTemplateName()
    {
        return "sms/offer-reminder.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "sms/offer-reminder.twig", "/app/templates/sms/offer-reminder.twig");
    }
}
