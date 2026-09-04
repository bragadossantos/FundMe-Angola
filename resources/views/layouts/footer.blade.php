<footer class="footer-fundme text-white">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <a class="brand-logo text-white mb-3" href="{{ route('home') }}">
                    <div class="logo-icon">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <span>FundMe <span class="highlight">Angola</span></span>
                </a>
                <p class="text-white small mb-3">
                    Juntos podemos transformar solidariedade em esperança. Plataforma nacional angolana de crowdfunding solidário para causas médicas rigorosamente verificadas.
                </p>
                <div class="d-flex gap-2 flex-wrap text-white">
                    <span class="badge bg-success text-white p-2 fw-normal"><i class="bi bi-shield-check me-1"></i> Verificação Rigorosa</span>
                    <span class="badge bg-primary text-white p-2 fw-normal"><i class="bi bi-lock me-1"></i> Proteção de Dados</span>
                </div>
            </div>

            <div class="col-6 col-lg-2 ms-auto">
                <h5 class="footer-title text-white fw-bold">Plataforma</h5>
                <div class="footer-links">
                    <a href="{{ route('home') }}" class="text-white">Início</a>
                    <a href="{{ route('campaigns.index') }}" class="text-white">Campanhas Médicas</a>
                    <a href="{{ route('how_it_works') }}" class="text-white">Como Funciona</a>
                    <a href="{{ route('campaigns.create') }}" class="text-white">Pedir Ajuda</a>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <h5 class="footer-title text-white fw-bold">Transparência & Segurança</h5>
                <div class="footer-links">
                    <a href="{{ route('how_it_works') }}#verificacao" class="text-white">Processo de Verificação</a>
                    <a href="{{ route('how_it_works') }}#privacidade" class="text-white">Proteção de Dados Médicos</a>
                    <a href="{{ route('how_it_works') }}#destino" class="text-white">Destino dos Fundos</a>
                    <a href="{{ route('register') }}" class="text-white">Criar Conta de Doador</a>
                </div>
            </div>

            <div class="col-lg-3">
                <h5 class="footer-title text-white fw-bold">Contacto Oficial</h5>
                <p class="small text-white mb-2"><i class="bi bi-geo-alt me-2 text-warning"></i> Luanda, Angola</p>
                <p class="small text-white mb-2"><i class="bi bi-envelope me-2 text-warning"></i> suporte@fundmeangola.ao</p>
                <p class="small text-white mb-3"><i class="bi bi-telephone me-2 text-warning"></i> +244 923 000 000</p>
                <div class="border-top border-secondary pt-3 mt-3">
                    <span class="small text-white"><i class="bi bi-shield-lock-fill text-warning me-1"></i> Sistema 100% Auditado</span>
                </div>
            </div>
        </div>

        <div class="border-top border-secondary pt-3 d-flex flex-column flex-md-row justify-content-between align-items-center small text-white">
            <p class="mb-2 mb-md-0 text-white">&copy; {{ date('Y') }} FundMe Angola. Todos os direitos reservados.</p>
            <p class="mb-0 text-white">Desenvolvido com foco em Segurança, Transparência e Solidariedade.</p>
        </div>
    </div>
</footer>
