@extends('layouts.dashboard')

@section('header')
    @php
        $back = null;
    @endphp

    @component('layouts.components.header', ['subtitle' => 'Salvataggio preferenze', 'icon' => 'pe-7s-home', 'back' => $back])
        Privacy
    @endcomponent
@endsection

@section('content')


    <div class="row">
        <div class="col-lg-8">

            <h2>Informativa sul trattamento dei dati personali ex artt. 12-13-14 Reg.to UE 2016/679 con le integrazioni del D.Lgs 101/18 e ss.mm.ii.</h2>
            Ai sensi degli artt. 13-14 del Regolamento Europeo 2016/679 (di seguito GDPR), e in relazione ai suoi dati personali con la presente La informiamo che la citata normativa prevede la tutela degli interessati rispetto al trattamento dei dati personali e che tale trattamento sarà improntato ai principi di correttezza, liceità, trasparenza e di tutela della Sua riservatezza e dei Suoi diritti. I Suoi dati personali verranno trattati conformemente alle disposizioni della normativa sopra richiamata e degli obblighi di riservatezza ivi previsti. Pertanto, Le comunichiamo quanto segue:

            <br>
            <br>
            <b>Gentile interessato,</b>
            <br>
            la presente per informarLa di come saranno trattati i suoi dati in base alla nuova normativa sulla privacy, Reg. EU 679/2016, D. Lgs 101/18 e ss.mm. ii.. Il Titolare la informa che la citata normativa prevede la tutela degli interessati rispetto al trattamento dei dati personali e che tale trattamento sarà improntato ai principi di correttezza, liceità, trasparenza e di tutela della Sua riservatezza e dei Suoi diritti.

            <h3 class="text-uppercase mt-2">Titolare del trattamento e responsabile della protezione dei dati personali</h3>
            Titolare del trattamento è La società {{ $azienda ? $azienda->label : 'Nome azienda' }}, in persona del legale rappresentante {{ $azienda ? $azienda->legale_rappresentante : 'Nome legale rappresentante' }} con sede in {{ $azienda ? $azienda->indirizzo : 'Indirizzo sede' }}, CAP {{ $azienda ? $azienda->cap : 'CAP sede' }}, di seguito solo “Titolare” può essere contattato mediante tel. {{ $azienda ? $azienda->legale_rappresentante_tel : 'Telefono legale rappresentante' }} o per posta elettronica all’indirizzo: {{ $azienda ? $azienda->legale_rappresentante_email : 'Email legale rappresentante' }}.
            Il Titolare ha nominato un RPD (responsabile della protezione dei dati personali o DPO) ai sensi dell’art. 37 del suddetto Regolamento, nella figura di {{ $azienda ? $azienda->rpd : 'Nome rpd' }}, raggiungibile all’indirizzo: {{ $azienda ? $azienda->rpd_email : 'Rdp email' }}.
            Il titolare del Trattamento ha nominato contestualmente alla sottoscrizione del contratto con la società Infosituata Srls, fornitrice della piattaforma software sui cui è basato il servizio, la stessa Responsabile del Trattamento dei Suoi dati ai sensi dell’art. 28 del Reg. EU 679/2016.
            Inoltre, la società Infosituata Srls, in chiaro e per iscritto nei confronti del Titolare del Trattamento, ha reso edotto il Titolare che per alcuni servizi critici e per alzare i livelli di sicurezza dei dati trattati negli interessi degli stessi interessati/utilizzatori finali e per meglio rispondere alle esigenze di riservatezza, disponibilità ed integrità del dato, come richiesto dal Reg. EU 679/2016, ha sottoscritto regolare contratto con una società di primaria rilevanza nazionale, come Sub-Responsabile del Trattamento dei Suoi dati ai sensi dell’art. 28 del GDPR.
            Per maggiori informazioni su come verranno trattati i suoi dati, oltre ai contatti del Titolare sopra citati, potrà inviare richiesta a <a href="mailto:admin@infosituata.it">admin@infosituata.it</a>.

            <h3 class="text-uppercase mt-2">Natura dei dati trattati</h3>
            I dati personali da lei fornitici sono dati definiti dalla legge come dati “Personali”, quali ad esempio dati anagrafici, inerenti la salute e/o la sua geolocalizzazione durante le sue ore di lavoro. Precisiamo che, nel trattare tali dati, ci atterremo scrupolosamente ai limiti ed alle condizioni imposti dal rapporto preindicato ai sensi dell’art. 6 c.1 lett. b), c) ed f); e nel caso di eventuali dati personali definiti dalla legge come dati “particolari” (ex-sensibili), ci atterremo scrupolosamente ai limiti ed alle condizioni imposti dal rapporto preindicato, e secondo le eccezioni previste dall’art. 9 del GDPR e dell’art. 2 del D.Lgs 101/18, ed in generale per la salvaguardia e tutela della salute dell’interessato come richiamato dall’art. 9 comma 2 lettere b) e c).

            <h3 class="text-uppercase mt-2">Finalità del trattamento dei dati</h3>
            I Suoi dati verranno trattati per le seguenti finalità connesse all'attuazione di adempimenti relativi ad obblighi legislativi o contrattuali ai sensi dell’art. 6 lett. b), c) ed f) del GDPR, anche senza il suo espresso consenso (art.6 c.1 lett. b) e c) del GDPR):
            <ul>
                <li>per raccolta e conservazione dei dati ai fini della sicurezza sul lavoro e della sorveglianza sanitaria.</li>
                <li>adempiere agli obblighi precontrattuali e contrattuali</li>
                <li>rispettare gli altri obblighi incombenti sul titolare previsti dalla normativa vigente</li>
                <li>esercitare i diritti del Titolare del trattamento, ad esempio il diritto di difesa in giudizio</li>
                <li>comunicazioni a soggetti terzi, identificati come responsabili del trattamento dal Titolare medesimo, per finalità amministrative e/o di gestione e sistemi informatici</li>
            </ul>

            Il trattamento dei dati funzionali per l'espletamento di tali obblighi è necessario per una corretta gestione del rapporto e il loro conferimento è obbligatorio per attuare le finalità sopra indicate. Il Titolare rende noto, inoltre, che l'eventuale non comunicazione, o comunicazione errata, di una delle informazioni obbligatorie, può causare l'impossibilità del Titolare di garantire la congruità del trattamento stesso.

            <h3 class="text-uppercase mt-2">Modalità del trattamento</h3>
            Il trattamento dei Suoi dati personali è realizzato per mezzo delle operazioni indicate all’art. 4 n. 2) GDPR e precisamente: raccolta, registrazione, conservazione, consultazione, comunicazione, cancellazione e diffusione dei dati. I Suoi dati personali sono sottoposti a trattamento sia cartaceo che elettronico e/o automatizzato.
            Ogni trattamento avviene nel rispetto delle modalità di cui agli artt. 6 e 32 del GDPR e mediante l'adozione delle adeguate misure di sicurezza previste. I dati sanitari o quelli sulla geolocalizzazione sono, se attivati dal Titolare del Trattamento, trattati in maniera anonima o pseudonimizzati.

            <h3 class="text-uppercase mt-2">Comunicazione e diffusione dei dati</h3>
            Senza la necessità di un espresso consenso ai sensi dell’art. 6 comma 1 lett. b), c) e f) del GDPR, il Titolare potrà comunicare i Suoi dati per le finalità di cui sopra ad Organismi di vigilanza, Autorità giudiziarie, nonché a quei soggetti ai quali la comunicazione sia obbligatoria per legge e per l’espletamento delle finalità suddette.
            <br>
            <br>
            I Suoi dati saranno comunicati esclusivamente a soggetti competenti e debitamente nominati, ove applicabile, come Responsabili del Trattamento, per l'espletamento dei servizi necessari ad una corretta gestione del rapporto, con garanzia di tutela dei diritti dell'interessato; a titolo di esempio:
            <ul>
                <li>consulenti e/o legali che eroghino prestazioni funzionali ai fini sopra indicati, che svolgono attività in outsourcing per conto del Titolare del trattamento, nominati responsabili esterni del trattamento;</li>
                <li>soggetti ed enti anche pubblici che elaborino i dati in esecuzione di specifici obblighi di legge;</li>
                <li>Autorità giudiziarie o amministrative, per l’adempimento degli obblighi di legge.</li>
            </ul>

            <h3 class="text-uppercase mt-2">Trasferimento dati in un paese Extra-UE</h3>
            I Suoi dati personali non saranno in alcun modo oggetto di trasferimento verso Paesi terzi extra UE o verso organizzazioni internazionali.

            <h3 class="text-uppercase mt-2">Conservazione dei dati</h3>
            Le segnaliamo che, nel rispetto dei principi di liceità, limitazione delle finalità e minimizzazione dei dati, ai sensi dell’art. 5 del GDPR, i Suoi dati personali, oggetto di trattamento per le finalità sopra indicate, saranno conservati per il periodo di durata del contratto e, successivamente, per il tempo in cui Il Titolare è soggetto ad obblighi di conservazione per finalità fiscali o per altre finalità previste, da norme di legge. Il periodo di conservazione dei Suoi dati personali è: di minimo 10 anni. Si precisa inoltre che i dati sulle condizioni di salute e di geolocalizzazione non vengono memorizzati e salvati; il loro impiego scatta solo in caso di emergenza e/o richiesta di aiuto da parte del lavoratore.

            <h3 class="text-uppercase mt-2">I Suoi diritti</h3>
            In ogni momento l’interessato potrà esercitare i Suoi diritti nei confronti del titolare del trattamento, previsti dagli artt. da 15 a 22 del Regolamento UE 2016/679 ove applicabili: diritto di accesso, rettifica, cancellazione, limitazione al trattamento, opposizione al trattamento. Si informa l’interessato che ha diritto di proporre reclamo all'autorità di controllo e può rivolgersi all’Autorità Garante per la protezione dei dati personali tramite il sito: www.garanteprivacy.it. In merito alle modalità di esercizio dei diritti previsti, l’interessato può inviare una mail all’indirizzo: {{ $azienda ? $azienda->email_contatto_privacy : 'Email contatto privacy' }}.
            <br>
            <br>
            I diritti di cui sopra, possono non applicarsi o avere delle limitazioni nella misura in cui il trattamento sia necessario:

            <h3 class="text-uppercase mt-2">Modifica informativa sulla Privacy</h3>
            Il titolare si riserva il diritto di modificare, aggiornare, aggiungere o rimuovere parti della presente informativa sulla privacy a propria discrezione e/o in base a successive integrazioni normative comunitaria o nazionale, e/o chiarimenti del Garante Italiano. Al fine di facilitare tale verifica l’informativa conterrà l’indicazione della data di aggiornamento.

            <br>
            <br>
            per conto del Titolare del trattamento
            <br>
            {{ $azienda ? $azienda->label : 'Nome azienda' }}


        </div>
        <div class="col-lg-4">

            @include('layouts.components.alerts.alert')

            <form action="{{ route('privacy.store') }}" method="post">
                @csrf

                <p>Per conto del Titolare del trattamento</p>
                <p>Confermi di aver preso visione dell’informativa privacy sopra indicata</p>
                @component('layouts.components.forms.checkbox', ['name' => 'privacy_fl_1', 'elements' => ['1' => 'Si'], 'value' => null])
                @endcomponent

                <button type="submit" class="btn btn-block btn-success">Salva</button>

            </form>

        </div>
    </div>

@endsection

