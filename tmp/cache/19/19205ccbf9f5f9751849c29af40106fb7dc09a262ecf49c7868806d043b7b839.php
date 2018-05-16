<?php

/* mail/offer-link.twig */
class __TwigTemplate_43c63c8557b6261a114ce5fc9809a7be2a8e6f7460503f0b50e4122cb5e461ea extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("mail/base-html.twig", "mail/offer-link.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "mail/base-html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "  <p style=\"margin:0 10px 0 0;Margin:0 10px 0 0;margin-bottom:25px;Margin-bottom:25px;padding:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:1.3;font-weight:400;text-align:left;\">
    Hi, ";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "application", array()), "fullName", array()), "html", null, true);
        echo "
  </p>
  <p style=\"margin:0 10px 0 0;Margin:0 10px 0 0;margin-bottom:25px;Margin-bottom:25px;padding:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:1.3;font-weight:400;text-align:left;\">
  Thank you for taking the time to explore the opportunities out there. <br> Please <a href=\"";
        // line 10
        echo twig_escape_filter($this->env, ($context["link"] ?? null), "html", null, true);
        echo "\">click here</a> to see, what wonderful offers we have for you.
  </p>
  <p style=\"margin:0 10px 0 0;Margin:0 10px 0 0;margin-bottom:25px;Margin-bottom:25px;padding:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:1.3;font-weight:400;text-align:left;\">
    If the link is not working, please copy and paste the following to your browser address bar and hit Enter:
  </p>
  <p style=\"margin:0 10px 0 0;Margin:0 10px 0 0;margin-bottom:25px;Margin-bottom:25px;padding:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:1.3;font-weight:400;text-align:left;word-break:break-all;\">
    <a target=\"_blank\" href=\"";
        // line 16
        echo twig_escape_filter($this->env, ($context["link"] ?? null), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, ($context["link"] ?? null), "html", null, true);
        echo "</a>
  <p>
";
    }

    public function getTemplateName()
    {
        return "mail/offer-link.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  60 => 16,  51 => 10,  45 => 7,  42 => 6,  39 => 5,  33 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "mail/offer-link.twig", "/app/templates/mail/offer-link.twig");
    }
}
