<?php

/* terms.twig */
class __TwigTemplate_6ae168e9ca442644cfdeeff7315a9140d62683807ab12abdd28020f289af762b extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.twig", "terms.twig", 1);
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
        echo "Terms and conditions";
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "<div class=\"row justify-content-lg-center\">
  <section class=\"col-sm-12 text-center\">
    <h1>Terms and conditions</h1>
  </section>
  <div class=\"col-sm-12 terms-container\">
    <h5>§ 1 Allmänna bestämmelser</h5>
    <ol>
        <li>Denna sekretesspolicy för mobilapplikationen för Aasa Kredit Svenska AB (nedan kallad \"Sekretesspolicyn\") är upprättad i enlighet med Personuppgiftslagen (1998: 204) och Allmänna dataskyddsförordningen (EU) 2016/679 och anger sättet att samla in, bearbeta och lagra personuppgifter som är nödvändiga för att driva mobilapplikationen (nedan kallad \"Applikationen\"), och för Aasa Kredit Svenska AB att erbjuda sina tjänster genom applikationen.</li>
        <li>I enlighet med personuppgiftslagen och Allmänna dataskyddsförordningen är Aasa Kredit Svenska AB med sin registrerade adress på S:t Eriksgatan 117, 113 43 Stockholm (nedan kallad “Registeransvarige”) is registeransvarig för personliga uppgifter beträffande användare av Applikationen.</li>
        <li>En användare av den mobila applikationen är alla som har Applikationen installerat på en mobilenhet och använder funktionaliteten som delas som en del av Applikationen (nedan kallad “Användaren”).</li>
        <li>Användaren tillhandahåller frivilligt Registeransvarige personliga uppgifter. Användarens personuppgifter behandlas av Registeransvarige för att installera Applikationen, för registrering i Applikationen och för att använda de tjänster som tillhandahålls som en del av Applikationen. Personuppgifter som tillhör Användaren behandlas så länge som Användaren har Applikationen installerad på en mobilenhet och använder Applikationens funktionalitet och därefter under en period för att säkerställa att personuppgifter inte oavsiktligt förloras.</li>
        <li>För de ändamål som avses i punkt 4 och för att säkerställa korrekt användning av Applikationen på enheter med Android-system måste användaren, under installationen av Applikationen tillåta att Applikationen får åtkomst till följande funktioner i användarens enhet:
            <ol style=\"list-style-type: lower-alpha;\">
                <li>Kalender - för att informera om förfallodatum för särskilda avbetalningar; genom att läsa informationen från kalendern, lägga till och ändra händelser i kalendern.</li>
                <li>Läsa mobilens tillstånd och information om den - i syfte att generera unika identifierare för Applikationen som behövs för att koda data och kommunicera med Registeransvariges server;</li>
                <li>Lista över applikationer - samlar information om applikationer som är installerade på enheten, för att upprätthålla säkerhet mot skadlig programvara</li>
                <li>Lagringsuppgifter (enhetens minne) - för att installera Applikationen på enheten;</li>
                <li>Nätverksinformation - för att kontrollera Internetanslutning via Applikationen;</li>
                <li>Plats – i syfte att korrekt hantera en kartapplikation på Användarens mobil för att hitta den närmaste fysiska platsen som tillhandahåller Registeransvariges tjänster;</li>
                <li>Historik för nätverkssamtal – visar aktuell historik för samtal med Registeransvarige, för statistiska ändamål;</li>
                <li>SMS - för att bekräfta de transaktioner som gjorts när du använder Applikationen, ta emot och skicka meddelanden;</li>
                <li>Telefonbok (kontakter) – i syfte att kontakta Aasa Kredit Svenska AB:s callcenter</li>
            </ol>
        </li>
        <li>Användarens personuppgifter kan behandlas av Registeransvarige och andra enheter som tillhör samma företagsgrupp som Registeransvarige för att tillhandahålla kommersiell information på elektronisk väg, till exempel e-post eller telefon, för marknadsföringstjänsteintresse och att ge användaren relevant information om Registeransvarige. Användaren kan när som helst välja bort att ta emot denna information genom att kontakta Registeransvarige.</li>
        <li>Kommunikationen mellan mobilapplikationen och internetkundservicesystemet utförs via TLS (Transport Layer Security) rapport och ytterligare kodningsmekanismer.</li>
        <li>Applikationen lagrar inte några personuppgifter som kan göra det möjligt för en tredje part att identifiera den särskilda användaren av Applikationen och lagrar inte data som tillåter autentisering i tjänsten eller användaruppgifter av något slag som endast är tillgängliga efter inloggning.</li>
        <li>Anonyma uppgifter om användarnas aktivitet i Applikationen kan behandlas av Registeransvarige för statistiska ändamål.</li>
    </ol>


    <h5>§ 2 Registeransvariges skyldigheter</h5>
    <ol>
        <li>Registeransvarige skall behandla Användarens personuppgifter i enlighet med den svenska Personuppgiftslagen (1998:204) fram till och med 25 maj 2018. Från och med 25 maj 2018, skall Registeransvarige behandla personuppgifter i enlighet med den Allmänna Dataskyddsförordningen (EU) 2016/679.</li>
        <li>Registeransvarige ska vidta relevanta tekniska och organisatoriska åtgärder för att säkerställa säkerheten för de personuppgifter som behandlas, särskilt för att förhindra åtkomst för obehöriga tredje parter och behandling av uppgifter i strid med bestämmelser i allmänt tillämplig lag som förhindrar förlust, skada eller förstörelse av personuppgifter.</li>
    </ol>

    <h5>§ 3 Användarens rättigheter</h5>
    <p>Till den 25 maj 2018 ska Användaren ha följande rättigheter:</p>
    <ol>
        <li>Användaren har rätt till åtkomst till sina personuppgifter via Applikationen.</li>
        <li>Användaren kan också begära att Registeransvarige modifierar, ändrar, kompletterar eller raderar personuppgifter under behandling. Registeransvarige skall uppfylla sådana förfrågningar i enlighet med personuppgiftslagen.</li>
        <li>I fallet med permanent borttagning av Användarens personuppgifter som är nödvändig för att Registeransvarige ska kunna utföra tjänster som tillhandahålls via Applikationen, förlorar Användaren möjligheten att använda tjänster som tillhandahålls via Applikationen.</li>
        <li>Användaren kan också begära från Registeransvarige en gång per år ett register över personuppgifter för den Användare som är under behandling, även när personuppgifterna samlades in från ändamålen med behandlingen och eventuella mottagare av personuppgifter.</li>
    </ol>
    <p>Från den 25 maj 2018 ska Användaren ha följande rättigheter:</p>
    <ol>
      <li>Användaren har rätt till åtkomst till sina personuppgifter via Applikationen.</li>
      <li>Användaren kan också begära att Registeransvarige modifierar, korrigerar eller raderar personuppgifter under behandling. Registeransvarige skall uppfylla sådana förfrågningar i enlighet med Allmänna dataskyddsförordningen.</li>
      <li>Användaren får invända mot ytterligare behandling. Registeransvarige skall uppfylla sådana förfrågningar i enlighet med Allmänna dataskyddsförordningen.</li>
      <li>I fallet med permanent borttagning av Användarens personuppgifter som är nödvändig för att Registeransvarige ska kunna utföra tjänster som tillhandahålls via Applikationen, förlorar Användaren möjligheten att använda tjänster som tillhandahålls via Applikationen.</li>
      <li>Användaren kan också när som helst begära från Registeransvarig ett register över sina personuppgifter under behandling, inklusive information om behandlingen.</li>
      <li>Användaren kan också begära personuppgifterna under bearbetning från Registeransvarige i ett format som är strukturerat och allmänt använt, i enlighet med Allmänna dataskyddsförordningen.</li>
      <li>Användaren har rätt att lämna in ett klagomål till den svenska dataskyddsmyndigheten om Registeransvariges behandling av personuppgifter.</li>
    </ol>

    <h5>§ 4 Erbjudna tjänster</h5>
    <ol>
        <li>Genom applikationen erbjuder Registeransvarige tjänster till Användaren som kräver behandling av personuppgifter. Behandling av personuppgifter för att tillhandahålla dessa tjänster genomförs i enlighet med Aasa Kredit Svenska AB:s policy för behandling av personuppgifter. För mer information om hur Aasa Kredit hanterar dina personuppgifter, besök <a href=\"http://www.aasakredit.se/sekretesspolicy\">http://www.aasakredit.se/sekretesspolicy</a></li>
    </ol>

    <h5>§ 5 Slutliga bestämmelser</h5>
    <ol>
        <li>Användaren är ombedd att acceptera villkoren som beskrivs i detta dokument vid installation av Applikationen på mobilenheten.</li>
        <li>När det gäller underlåtenhet att ge samtycke till denna sekretesspolicy, kan inte användaren installera Applikationen eller använda dess tjänster. Användaren ska också avinstallera Applikationen om Användaren inte längre accepterar villkoren som beskrivs i detta dokument.</li>
        <li>Registeransvarige förbehåller att införa ändringar av denna sekretesspolicy och samtidigt säkerställa att alla rättigheter för Användaren som härrör från detta dokument inte kommer att begränsas utan Användarens samtycke.</li>
        <li>Eventuella ändringar av denna sekretesspolicy ska publiceras på Registeransvariges webbplats: <a href=\"https://www.aasakredit.se/\">https://www.aasakredit.se/</a></li>
    </ol>
    <p><strong>Kontaktuppgifter</strong></p>
    <p>För att utöva rättigheter som tillåts enligt § 3 eller återkalla samtycke kan användaren kontakta Registeransvarige, kontaktuppgifter finns på: <a href=\"https://www.aasakredit.se/kontakt\">https://www.aasakredit.se/kontakt</a></p>

  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "terms.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  42 => 4,  39 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "terms.twig", "/app/templates/terms.twig");
    }
}
