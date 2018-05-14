<?php

/* admin/partner.twig */
class __TwigTemplate_26d5176782395546d4735c4da509dd3f48ea258ae712aadfa815a13f39a022e6 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "admin/partner.twig", 1);
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
        echo "Partner info: ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "name", array()), "html", null, true);
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "    <ol>
        <li><strong>ID:</strong> ";
        // line 5
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "id", array()), "html", null, true);
        echo "</li>
        <li><strong>Name:</strong> ";
        // line 6
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "name", array()), "html", null, true);
        echo "</li>
        <li><strong>Identifier:</strong> ";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "identifier", array()), "html", null, true);
        echo "</li>
        <li><strong>Status:</strong> ";
        // line 8
        if (twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "status", array())) {
            echo "Enabled";
        } else {
            echo "Disabled";
        }
        echo "</li>
        <li><strong>Commission:</strong> ";
        // line 9
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "commission", array()), "html", null, true);
        echo "</li>
        <li><strong>UseAPI:</strong> ";
        // line 10
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "useApi", array()), "html", null, true);
        echo "</li>
        <li><strong>APILiveUrl:</strong> ";
        // line 11
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "apiLiveUrl", array()), "html", null, true);
        echo "</li>
        <li><strong>APITestUrl:</strong>";
        // line 12
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "apiTestUrl", array()), "html", null, true);
        echo "</li>
        <li><strong>Logo:</strong> <img src=\"";
        // line 13
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "logo", array()), "html", null, true);
        echo "\"/></li>
    </ol>
    <a href=\"/admin/partners/update/";
        // line 15
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "id", array()), "html", null, true);
        echo "\" class=\"btn broker-btn broker-btn-blue mb-3\">Edit</a>
";
    }

    public function getTemplateName()
    {
        return "admin/partner.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  87 => 15,  82 => 13,  78 => 12,  74 => 11,  70 => 10,  66 => 9,  58 => 8,  54 => 7,  50 => 6,  46 => 5,  43 => 4,  40 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "admin/partner.twig", "/app/templates/admin/partner.twig");
    }
}
