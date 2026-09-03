<footer class="footer-fundme">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <a class="brand-logo text-white mb-3" href="{{ route('home') }}">
                    <div class="logo-icon">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <span>FundMe <span class="highlight">Angola</span></span>
                </a>
                <p class="text-muted small">
                    Juntos podemos transformar solidariedade em esperança. Plataforma nacional angolana de crowdfunding solidário para causas médicas rigorosamente verificadas.
                </p>
                <div class="d-flex gap-3 text-muted">
                    <span class="badge bg-success bg-opacity-25 text-success p-2"><i class="bi bi-shield-check me-1"></i> Verificação Rigorosa</span>
                    <span class="badge bg-primary bg-opacity-25 text-primary p-2"><i class="bi bi-lock me-1"></i> Proteção de Dados</span>
                </div>
            </div>

            <div class="col-6 col-lg-2 ms-auto">
                <h5 class="footer-title">Plataforma</h5>
                <div class="footer-links">
                    <a href="{{ route('home') }}">Início</a>
                    <a href="{{ route('campaigns.index') }}">Campanhas Médicas</a>
                    <a href="{{ route('how_it_works') }}">Como Funciona</a>
                    <a href="{{ route('campaigns.create') }}">Pedir Ajuda</a>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <h5 class="footer-title">Transparência & Segurança</h5>
                <div class="footer-links">
                    <a href="{{ route('how_it_works') }}#verificacao">Processo de Verificação</a>
                    <a href="{{ route('how_it_works') }}#privacidade">Proteção de Dados Médicos</a>
                    <a href="{{ route('how_it_works') }}#destino">Destino dos Fundos</a>
                    <a href="{{ route('register') }}">Criar Conta de Doador</a>
                </div>
            </div>

            <div class="col-lg-3">
                <h5 class="footer-title">Contacto Oficial</h5>
                <p class="small text-muted mb-1"><i class="bi bi-geo-alt me-2 text-warning"></i> Luanda, Angola</p>
                <p class="small text-muted mb-1"><i class="bi bi-envelope me-2 text-warning"></i> suporte@fundmeangola.ao</p>
                <p class="small text-muted mb-3"><i class="bi bi-telephone me-2 text-warning"></i> +244 923 000 000</p>
                <div class="border-top border-secondary pt-3 mt-3">
                    <span class="small text-muted">🛡️ Sistema 100% Auditado</span>
                </div>
            </div>
        </div>

        <div class="border-top border-secondary pt-3 d-flex flex-column flex-md-row justify-content-between align-items-center small text-muted">
            <p class="mb-2 mb-md-0">&copy; {{ date('Y') }} FundMe Angola. Todos os direitos reservados.</p>
            <p class="mb-0">Desenvolvido com foco em Segurança, Transparência e Solidariedade.</p>
        </div>
    </div>
</footer>
