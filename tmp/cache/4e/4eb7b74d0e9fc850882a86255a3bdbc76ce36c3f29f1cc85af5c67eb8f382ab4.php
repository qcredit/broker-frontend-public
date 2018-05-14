<?php

/* application/offer-list.twig */
class __TwigTemplate_806114f5bc17157a95577adc04113a9910db8196ff10613d801808a5b9cccc2c extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.twig", "application/offer-list.twig", 1);
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
        echo "Application's offer list";
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "  <div class=\"row justify-content-md-center\">
    <div class=\"col-sm-12 col-md-9\">
      <div class=\"offer-list-head\">
        <p>Thank you! Your application has been submitted and the loan offers will appear live within 15 minutes below:</p>
      </div>
    </div>
  </div>
  <section class=\"row justify-content-md-center\">
    <h4 class=\"col-sm-12 col-md-9 offers-list-heading\">Personal offers:</h4>
    ";
        // line 13
        if (twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "offers", array())) {
            // line 14
            echo "    <div class=\"col-sm-12 col-md-9 offers-listing-wrap\">
      ";
            // line 15
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "offers", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["offer"]) {
                // line 16
                echo "      <article class=\"loan-offer-container personal-offer-container ";
                if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["offer"], "partner", array()), "featured", array())) {
                    echo "featured";
                }
                echo "\">
        <div class=\"loan-offer-head\">
          <div class=\"loan-offerer-logo-container\">
            <img src=\"/src/images/aasalogo.png\" class=\"loan-offerer-logo\" alt=\"";
                // line 19
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["offer"], "partner", array()), "name", array()), "html", null, true);
                echo " logo\">
          </div>
          <div class=\"loan-offerer-info\">
            <span class=\"loan-offerer-name\">";
                // line 22
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["offer"], "partner", array()), "name", array()), "html", null, true);
                echo "</span>
            <span class=\"loan-offerer-website\">www.aasa.fi</span>
          </div>
          <div class=\"text-focus show-mobile\"><span class=\"name\">Monthly:</span> <span class=\"value\">";
                // line 25
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "monthlyFee", array()), "html", null, true);
                echo "PLN</span></div>
        </div>
        <div class=\"loan-offer-body\">
          <span><strong>Amount:</strong> ";
                // line 28
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "loanAmount", array()), "html", null, true);
                echo " PLN</span>
          <span><strong>Duration:</strong> ";
                // line 29
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "loanTerm", array()), "html", null, true);
                echo "m</span>
          <span><strong>Interest:</strong> ";
                // line 30
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "interest", array()), "html", null, true);
                echo " %</span>
          <span><strong>APR:</strong> ";
                // line 31
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "apr", array()), "html", null, true);
                echo "%</span>
        </div>
        <div class=\"loan-offer-footer\">
          <div class=\"text-focus show-large\"><span class=\"name\">Monthly:</span> <span class=\"value\">";
                // line 34
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "monthlyFee", array()), "html", null, true);
                echo "PLN</span></div>
          <a href=\"";
                // line 35
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "acceptancePageUrl", array()), "html", null, true);
                echo "\" target=\"_blank\" class=\"loan-offer-cta btn broker-btn broker-btn-gold\">SIGN CONTRACT</a>
        </div>
        <!--
        <p>";
                // line 38
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["offer"], "partner", array()), "id", array()), "html", null, true);
                echo "</p>
        <p>";
                // line 39
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["offer"], "partner", array()), "name", array()), "html", null, true);
                echo "</p>
        <p>";
                // line 40
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "loanAmount", array()), "html", null, true);
                echo "</p>
        <p>";
                // line 41
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["offer"], "remoteId", array()), "html", null, true);
                echo "</p>
        <p>";
                // line 42
                if (twig_get_attribute($this->env, $this->source, $context["offer"], "isInProcess", array())) {
                    echo "In process";
                } elseif (twig_get_attribute($this->env, $this->source, $context["offer"], "isPaidOut", array())) {
                    echo "Paid out";
                } else {
                    echo "Rejected";
                }
                echo "</p>-->
      </article>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['offer'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 45
            echo "    </div>
    ";
        }
        // line 47
        echo "  </section>
  <div class=\"row justify-content-sm-center\">
    <div class=\"col-sm-9\">
      <div class=\"bouncing-loader\">
        <div class=\"bouncing-loader__round\"></div>
        <div class=\"bouncing-loader__round\"></div>
        <div class=\"bouncing-loader__round\"></div>
      </div>
    </div>
  </div>
  <section class=\"row justify-content-md-center\">
    <h4 class=\"col-sm-12 col-md-9 offers-list-heading\">Other offers:</h4>
    <div class=\"col-sm-12 col-md-9 offers-listing-wrap\">
      <article class=\"loan-offer-container affiliate-offer-container\">
        <div class=\"loan-offer-head\">
          <div class=\"loan-offerer-logo-container\">
            <img src=\"/src/images/affiliate_logos/provident.jpg\" class=\"loan-offerer-logo\" alt=\"Provident logo\">
          </div>
          <div class=\"loan-offerer-info\">
            <span class=\"loan-offerer-name\">Provident</span>
            <span class=\"loan-offerer-website\">www.provident.pl</span>
          </div>
        </div>
        <div class=\"loan-offer-body\">
          <span><strong>Amount:</strong> 500 - 15000 PLN</span>
          <span><strong>Duration:</strong> 3 - 36 m</span>
          <span><strong>APR:</strong> 78% - 251%</span>
        </div>
        <div class=\"loan-offer-footer\">
          <a href=\"http://tracking.affiliate44.com/aff_c?offer_id=24&aff_id=447\" target=\"_blank\" class=\"loan-offer-cta btn broker-btn broker-btn-gold\">Apply for a loan</a>
        </div>
      </article>
      <article class=\"loan-offer-container affiliate-offer-container\">
        <div class=\"loan-offer-head\">
          <div class=\"loan-offerer-logo-container\">
            <img src=\"/src/images/affiliate_logos/sms-kredyt-logo.png\" class=\"loan-offerer-logo\" alt=\"smskredyt logo\">
          </div>
          <div class=\"loan-offerer-info\">
            <span class=\"loan-offerer-name\">Smskredyt</span>
            <span class=\"loan-offerer-website\">www.smskredyt.pl</span>
          </div>
        </div>
        <div class=\"loan-offer-body\">
          <span><strong>Amount:</strong> 1000 - 7000 PLN</span>
          <span><strong>Duration:</strong> 3 - 30 m</span>
          <span><strong>APR:</strong> 97% - 538%</span>
        </div>
        <div class=\"loan-offer-footer\">
          <a href=\"http://tracking.affiliate44.com/aff_c?offer_id=41&aff_id=447\" target=\"_blank\" class=\"loan-offer-cta btn broker-btn broker-btn-gold\">Apply for a loan</a>
        </div>
      </article>
      <article class=\"loan-offer-container affiliate-offer-container\">
        <div class=\"loan-offer-head\">
          <div class=\"loan-offerer-logo-container\">
            <img src=\"/src/images/affiliate_logos/hapipozyczki.png\" class=\"loan-offerer-logo\" alt=\"Hapipozyczki logo\">
          </div>
          <div class=\"loan-offerer-info\">
            <span class=\"loan-offerer-name\">Hapipozyczki</span>
            <span class=\"loan-offerer-website\">www.hapipozyczki.pl</span>
          </div>
        </div>
        <div class=\"loan-offer-body\">
          <span><strong>Amount:</strong> 800 - 15000 PLN</span>
          <span><strong>Duration:</strong> 3 - 36 m</span>
          <span><strong>APR:</strong> 78% - 491%</span>
        </div>
        <div class=\"loan-offer-footer\">
          <a href=\"http://tracking.affiliate44.com/aff_c?offer_id=47&aff_id=447\" target=\"_blank\" class=\"loan-offer-cta btn broker-btn broker-btn-gold\">Apply for a loan</a>
        </div>
      </article>
      <article class=\"loan-offer-container affiliate-offer-container\">
        <div class=\"loan-offer-head\">
          <div class=\"loan-offerer-logo-container\">
            <img src=\"/src/images/affiliate_logos/logo-pozyczkaok.png\" class=\"loan-offerer-logo\" alt=\"Pozyczkaok logo\">
          </div>
          <div class=\"loan-offerer-info\">
            <span class=\"loan-offerer-name\">Pozyczkaok</span>
            <span class=\"loan-offerer-website\">www.pozyczkaok.pl</span>
          </div>
        </div>
        <div class=\"loan-offer-body\">
          <span><strong>Amount:</strong> 1500 - 7000 PLN</span>
          <span><strong>Duration:</strong> 6 - 30 m</span>
          <span><strong>APR:</strong> 97% - 261%</span>
        </div>
        <div class=\"loan-offer-footer\">
          <a href=\"http://tracking.affiliate44.com/aff_c?offer_id=91&aff_id=447\" target=\"_blank\" class=\"loan-offer-cta btn broker-btn broker-btn-gold\">Apply for a loan</a>
        </div>
      </article>
      <article class=\"loan-offer-container affiliate-offer-container\">
        <div class=\"loan-offer-head\">
          <div class=\"loan-offerer-logo-container\">
            <img src=\"/src/images/affiliate_logos/logoHomekredyt.png\" class=\"loan-offerer-logo\" alt=\"HomeKredyt logo\">
          </div>
          <div class=\"loan-offerer-info\">
            <span class=\"loan-offerer-name\">HomeKredyt</span>
            <span class=\"loan-offerer-website\">www.homekredyt.pl</span>
          </div>
        </div>
        <div class=\"loan-offer-body\">
          <span><strong>Amount:</strong> 3000 - 15000 PLN</span>
          <span><strong>Duration:</strong> 6 - 24 m</span>
          <span><strong>APR:</strong> 80% - 271%</span>
        </div>
        <div class=\"loan-offer-footer\">
          <a href=\"http://tracking.affiliate44.com/aff_c?offer_id=111&aff_id=447\" target=\"_blank\" class=\"loan-offer-cta btn broker-btn broker-btn-gold\">Apply for a loan</a>
        </div>
      </article>
      <article class=\"loan-offer-container affiliate-offer-container\">
        <div class=\"loan-offer-head\">
          <div class=\"loan-offerer-logo-container\">
            <img src=\"/src/images/affiliate_logos/supergrosz.png\" class=\"loan-offerer-logo\" alt=\"Supergrosz logo\">
          </div>
          <div class=\"loan-offerer-info\">
            <span class=\"loan-offerer-name\">Supergrosz</span>
            <span class=\"loan-offerer-website\">www.supergrosz.pl</span>
          </div>
        </div>
        <div class=\"loan-offer-body\">
          <span><strong>Amount:</strong> 1000 - 15000 PLN</span>
          <span><strong>Duration:</strong> 4 - 48 m</span>
          <span><strong>APR:</strong> 58% - 378%</span>
        </div>
        <div class=\"loan-offer-footer\">
          <a href=\"http://tracking.affiliate44.com/aff_c?offer_id=150&aff_id=447\" target=\"_blank\" class=\"loan-offer-cta btn broker-btn broker-btn-gold\">Apply for a loan</a>
        </div>
      </article>
      <article class=\"loan-offer-container affiliate-offer-container\">
        <div class=\"loan-offer-head\">
          <div class=\"loan-offerer-logo-container\">
            <img src=\"/src/images/affiliate_logos/euroloan-logo-100.png\" class=\"loan-offerer-logo\" alt=\"Euroloan logo\">
          </div>
          <div class=\"loan-offerer-info\">
            <span class=\"loan-offerer-name\">Euroloan</span>
            <span class=\"loan-offerer-website\">www.euroloan.pl</span>
          </div>
        </div>
        <div class=\"loan-offer-body\">
          <span><strong>Amount:</strong> 2000 - 20000 PLN</span>
          <span><strong>Duration:</strong> 5 - 48 m</span>
          <span><strong>APR:</strong> 47% - 154%</span>
        </div>
        <div class=\"loan-offer-footer\">
          <a href=\"http://tracking.affiliate44.com/aff_c?offer_id=458&aff_id=447\" target=\"_blank\" class=\"loan-offer-cta btn broker-btn broker-btn-gold\">Apply for a loan</a>
        </div>
      </article>
      <article class=\"loan-offer-container affiliate-offer-container\">
        <div class=\"loan-offer-head\">
          <div class=\"loan-offerer-logo-container\">
            <img src=\"/src/images/affiliate_logos/logo-ratka.png\" class=\"loan-offerer-logo\" alt=\"Ratka logo\">
          </div>
          <div class=\"loan-offerer-info\">
            <span class=\"loan-offerer-name\">Ratka</span>
            <span class=\"loan-offerer-website\">www.ratka.pl</span>
          </div>
        </div>
        <div class=\"loan-offer-body\">
          <span><strong>Amount:</strong> 1000 - 10000 PLN</span>
          <span><strong>Duration:</strong> 12 - 24 m</span>
          <span><strong>APR:</strong> 75% - 138%</span>
        </div>
        <div class=\"loan-offer-footer\">
          <a href=\"http://tracking.affiliate44.com/aff_c?offer_id=2&aff_id=447\" target=\"_blank\" class=\"loan-offer-cta btn broker-btn broker-btn-gold\">Apply for a loan</a>
        </div>
      </article>
    </div>
  </section>
  <script>
    var app_hash = '";
        // line 215
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "applicationHash", array()), "html", null, true);
        echo "';
    setInterval(function(){
      console.log(app_hash);
      \$.ajax({
        method: \"GET\",
        url: \"/application/status\",
      })
        .done(function( data ) {
          console.log( \"Success \" + data.status );
          if(data.status === 'done') {
            \$('.bouncing-loader').hide();
          }
        })
        .fail(function(){
          console.log('fail');
        });
    },60000);
  </script>
";
    }

    public function getTemplateName()
    {
        return "application/offer-list.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  322 => 215,  152 => 47,  148 => 45,  133 => 42,  129 => 41,  125 => 40,  121 => 39,  117 => 38,  111 => 35,  107 => 34,  101 => 31,  97 => 30,  93 => 29,  89 => 28,  83 => 25,  77 => 22,  71 => 19,  62 => 16,  58 => 15,  55 => 14,  53 => 13,  42 => 4,  39 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "application/offer-list.twig", "/app/templates/application/offer-list.twig");
    }
}
