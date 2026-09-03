<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Hospital;
use App\Models\Beneficiary;
use App\Models\Campaign;
use App\Models\CampaignFundPlan;
use App\Models\CampaignDocument;
use App\Models\CampaignVerification;
use App\Models\CampaignUpdate;
use App\Models\Donation;
use App\Models\PaymentDestination;
use App\Models\AuditLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Roles
        $roles = [
            ['name' => 'Administrador', 'slug' => 'admin', 'description' => 'Acesso total de gestão e decisões financeiras.'],
            ['name' => 'Verificador', 'slug' => 'verifier', 'description' => 'Análise de documentos e recomendações de campanhas.'],
            ['name' => 'Solicitante', 'slug' => 'applicant', 'description' => 'Criação e acompanhamento de pedidos de campanha.'],
            ['name' => 'Doador', 'slug' => 'donor', 'description' => 'Apoio financeiro e acompanhamento de campanhas.'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['slug' => $r['slug']], $r);
        }

        // 2. Create Default System Users
        $admin = User::create([
            'name' => 'Administrador FundMe',
            'email' => 'admin@fundmeangola.ao',
            'phone' => '+244923000001',
            'role' => 'admin',
            'status' => 'active',
            'province' => 'Luanda',
            'municipality' => 'Talatona',
            'password' => Hash::make('password'),
        ]);

        $verifier = User::create([
            'name' => 'Dra. Maria Santos (Verificadora)',
            'email' => 'verificador@fundmeangola.ao',
            'phone' => '+244923000002',
            'role' => 'verifier',
            'status' => 'active',
            'province' => 'Luanda',
            'municipality' => 'Maianga',
            'password' => Hash::make('password'),
        ]);

        $applicant1 = User::create([
            'name' => 'Manuel Agostinho',
            'email' => 'solicitante@fundmeangola.ao',
            'phone' => '+244923111222',
            'role' => 'applicant',
            'status' => 'active',
            'province' => 'Luanda',
            'municipality' => 'Cazenga',
            'password' => Hash::make('password'),
        ]);

        $applicant2 = User::create([
            'name' => 'Esperança Domingos',
            'email' => 'esperanca@fundmeangola.ao',
            'phone' => '+244924333444',
            'role' => 'applicant',
            'status' => 'active',
            'province' => 'Huíla',
            'municipality' => 'Lubango',
            'password' => Hash::make('password'),
        ]);

        $donor1 = User::create([
            'name' => 'Teresa Neto',
            'email' => 'doador@fundmeangola.ao',
            'phone' => '+244925555666',
            'role' => 'donor',
            'status' => 'active',
            'province' => 'Luanda',
            'municipality' => 'Belas',
            'password' => Hash::make('password'),
        ]);

        $donor2 = User::create([
            'name' => 'António Fonseca',
            'email' => 'antonio@fundmeangola.ao',
            'phone' => '+244926777888',
            'role' => 'donor',
            'status' => 'active',
            'province' => 'Benguela',
            'municipality' => 'Lobito',
            'password' => Hash::make('password'),
        ]);

        // 3. Create Hospitals
        $hospital1 = Hospital::create([
            'name' => 'Complexo Hospitalar Cardeal Dom Alexandre do Nascimento',
            'province' => 'Luanda',
            'municipality' => 'Kilmba Kiaxi',
            'address' => 'Avenida Pedro de Castro Van-Dúnem Loy',
            'contact_phone' => '+244222001122',
            'contact_email' => 'financeiro@chdan.ao',
            'bank_name' => 'Banco Angolano de Investimentos (BAI)',
            'bank_account_number' => '0040.0000.123456789.10',
            'iban' => 'AO06.0040.0000.1234.5678.9101.4',
            'swift_bic' => 'BAIAAO22',
            'is_verified' => true,
        ]);

        $hospital2 = Hospital::create([
            'name' => 'Hospital Pedriático David Bernardino',
            'province' => 'Luanda',
            'municipality' => 'Ingombota',
            'address' => 'Rua Amílcar Cabral',
            'contact_phone' => '+244222334455',
            'contact_email' => 'tesouraria@hpedriatico.ao',
            'bank_name' => 'Banco de Poupança e Crédito (BPC)',
            'bank_account_number' => '0010.0000.987654321.10',
            'iban' => 'AO06.0010.0000.9876.5432.1010.5',
            'is_verified' => true,
        ]);

        // 4. Beneficiaries
        $beneficiary1 = Beneficiary::create([
            'user_id' => $applicant1->id,
            'full_name' => 'Maria Agostinho (Filha do Solicitante)',
            'age_range' => '6-12 anos',
            'relation_to_applicant' => 'Filho(a)',
            'location_province' => 'Luanda',
            'location_municipality' => 'Cazenga',
            'is_identity_hidden' => false,
        ]);

        $beneficiary2 = Beneficiary::create([
            'user_id' => $applicant2->id,
            'full_name' => 'João Domingos',
            'age_range' => '18-35 anos',
            'relation_to_applicant' => 'O próprio',
            'location_province' => 'Huíla',
            'location_municipality' => 'Lubango',
            'is_identity_hidden' => true, // Protected identity
        ]);

        // 5. Campaigns
        $campaign1 = Campaign::create([
            'user_id' => $applicant1->id,
            'beneficiary_id' => $beneficiary1->id,
            'hospital_id' => $hospital2->id,
            'title' => 'Cirurgia Cardíaca de Urgência para a Pequena Maria',
            'slug' => 'cirurgia-cardiaca-urgencia-pequena-maria',
            'short_description' => 'A Maria necessita de intervenção cirúrgica pediátrica urgente para correção de cardiopatia congénita.',
            'story' => "A Maria tem 8 anos de idade e foi diagnosticada com uma cardiopatia congénita complexa no Hospital Pediátrico David Bernardino.\n\nSem esta cirurgia corretiva, o seu quadro clínico corre sério risco de agravamento fatal. A família não dispõe dos meios financeiros para custear as despesas de internamento especializado, medicação pós-operatória e materiais cirúrgicos.\n\nTodo o apoio da comunidade angolana fará a diferença entre a vida e a esperança.",
            'category' => 'cirurgia',
            'target_amount' => 3500000.00,
            'raised_amount' => 1750000.00,
            'currency' => 'Kz',
            'status' => 'published',
            'payment_destination_type' => 'hospital_direct',
            'location_province' => 'Luanda',
            'location_municipality' => 'Cazenga',
            'hospital_name' => 'Hospital Pedriático David Bernardino',
            'treatment_location' => 'angola',
            'expected_treatment_date' => now()->addDays(30),
            'featured_image' => null,
            'is_featured' => true,
            'verification_badge' => true,
            'published_at' => now()->subDays(10),
        ]);

        // Fund breakdown items for Campaign 1
        CampaignFundPlan::create([
            'campaign_id' => $campaign1->id,
            'item_name' => 'Intervenção Cirúrgica Pediátrica',
            'estimated_amount' => 2500000.00,
            'notes' => 'Custo de bloco cirúrgico e equipa médica',
        ]);
        CampaignFundPlan::create([
            'campaign_id' => $campaign1->id,
            'item_name' => 'Internamento Pós-Operatório (Cuidados Intensivos)',
            'estimated_amount' => 500000.00,
            'notes' => '7 dias em UTI pediátrica',
        ]);
        CampaignFundPlan::create([
            'campaign_id' => $campaign1->id,
            'item_name' => 'Exames e Medicação Especializada',
            'estimated_amount' => 500000.00,
            'notes' => 'Ecocardiogramas e profilaxia',
        ]);

        // Campaign 2
        $campaign2 = Campaign::create([
            'user_id' => $applicant2->id,
            'beneficiary_id' => $beneficiary2->id,
            'hospital_id' => $hospital1->id,
            'title' => 'Tratamento Oncológico e Medicamentos de Alta Complexidade',
            'slug' => 'tratamento-oncologico-medicamentos-alta-complexidade',
            'short_description' => 'Apoio urgente para aquisição de medicação quimioterápica para jovem beneficiário no Lubango.',
            'story' => "O paciente necessita de ciclos continuados de medicação especializada oncológica, indisponível na rede pública regional no momento.\n\nOs fundos serão destinados estritamente para a compra direta junto dos fornecedores farmacêuticos certificados ou transferência verificada ao beneficiário conforme decisão da FundMe Angola.",
            'category' => 'medicamentos',
            'target_amount' => 2000000.00,
            'raised_amount' => 2000000.00,
            'currency' => 'Kz',
            'status' => 'goal_reached',
            'payment_destination_type' => 'beneficiary_transfer',
            'location_province' => 'Huíla',
            'location_municipality' => 'Lubango',
            'hospital_name' => 'Complexo Hospitalar Cardeal Dom Alexandre do Nascimento',
            'treatment_location' => 'angola',
            'expected_treatment_date' => now()->addDays(15),
            'featured_image' => null,
            'is_featured' => true,
            'verification_badge' => true,
            'published_at' => now()->subDays(20),
        ]);

        // Fund breakdown for Campaign 2
        CampaignFundPlan::create([
            'campaign_id' => $campaign2->id,
            'item_name' => '4 Ciclos de Quimioterapia Especializada',
            'estimated_amount' => 1600000.00,
        ]);
        CampaignFundPlan::create([
            'campaign_id' => $campaign2->id,
            'item_name' => 'Exames de Seguimento Laboratorial',
            'estimated_amount' => 400000.00,
        ]);

        // Private Documents for Campaign 1 (Strictly Confidential)
        CampaignDocument::create([
            'campaign_id' => $campaign1->id,
            'document_type' => 'identity_card',
            'original_name' => 'BI_Solicitante_Manuel.pdf',
            'file_path' => 'documents/private/bi_manuel_secured.pdf',
            'file_mime' => 'application/pdf',
            'file_size' => 102400,
            'is_private' => true,
            'verification_status' => 'approved',
            'uploaded_by' => $applicant1->id,
        ]);

        CampaignDocument::create([
            'campaign_id' => $campaign1->id,
            'document_type' => 'medical_report',
            'original_name' => 'Relatorio_Medico_Cardiopatia_Pediátrico.pdf',
            'file_path' => 'documents/private/relatorio_medico_maria_secured.pdf',
            'file_mime' => 'application/pdf',
            'file_size' => 245000,
            'is_private' => true,
            'verification_status' => 'approved',
            'uploaded_by' => $applicant1->id,
        ]);

        // Verifications
        CampaignVerification::create([
            'campaign_id' => $campaign1->id,
            'verifier_id' => $verifier->id,
            'action' => 'approved',
            'internal_notes' => 'Relatório médico do Hospital Pediátrico conferido e válido. Identidade do solicitante confirmada via BI. Destino dos fundos aprovado para Pagamento Direto à Instituição Médica.',
        ]);

        // Updates
        CampaignUpdate::create([
            'campaign_id' => $campaign1->id,
            'user_id' => $applicant1->id,
            'title' => 'Campanha Aprovada e 50% da Meta Atingida!',
            'content' => 'Com imensa gratidão informamos que a campanha já alcançou metade do valor necessário graças à generosidade de todos os doadores. Continuem a partilhar!',
            'is_public' => true,
            'approved_by' => $admin->id,
        ]);

        CampaignUpdate::create([
            'campaign_id' => $campaign2->id,
            'user_id' => $admin->id,
            'title' => '🎯 Meta Atingida! Processamento de Pagamento Iniciado',
            'content' => 'A meta financeira de 2.000.000 Kz foi atingida com sucesso. A FundMe Angola deu início ao processo administrativo e verificação final dos comprovativos para destinação dos recursos.',
            'is_public' => true,
            'approved_by' => $admin->id,
        ]);

        // Donations for Campaign 1
        Donation::create([
            'campaign_id' => $campaign1->id,
            'user_id' => $donor1->id,
            'donor_name' => 'Teresa Neto',
            'donor_email' => 'doador@fundmeangola.ao',
            'amount' => 1000000.00,
            'currency' => 'Kz',
            'status' => 'paid',
            'payment_method' => 'multicaixa_express',
            'payment_reference' => 'FMA-DON-001',
            'is_anonymous' => false,
            'donor_message' => 'Que Deus abençoe a cirurgia da pequena Maria. Força à família!',
            'paid_at' => now()->subDays(5),
        ]);

        Donation::create([
            'campaign_id' => $campaign1->id,
            'user_id' => $donor2->id,
            'donor_name' => 'António Fonseca',
            'donor_email' => 'antonio@fundmeangola.ao',
            'amount' => 750000.00,
            'currency' => 'Kz',
            'status' => 'paid',
            'payment_method' => 'bank_transfer',
            'payment_reference' => 'FMA-DON-002',
            'is_anonymous' => false,
            'donor_message' => 'Juntos pela saúde em Angola!',
            'paid_at' => now()->subDays(2),
        ]);

        // Donations for Campaign 2
        Donation::create([
            'campaign_id' => $campaign2->id,
            'user_id' => null,
            'donor_name' => 'Empresa Solidária de Luanda',
            'donor_email' => 'contacto@solidaria.ao',
            'amount' => 2000000.00,
            'currency' => 'Kz',
            'status' => 'paid',
            'payment_method' => 'bank_transfer',
            'payment_reference' => 'FMA-DON-003',
            'is_anonymous' => true,
            'donor_message' => 'Desejamos plena recuperação.',
            'paid_at' => now()->subDays(1),
        ]);

        // Payment destination record for Campaign 1 (Admin setting)
        PaymentDestination::create([
            'campaign_id' => $campaign1->id,
            'destination_type' => 'hospital_direct',
            'institution_or_payee_name' => 'Hospital Pedriático David Bernardino',
            'bank_name' => 'Banco de Poupança e Crédito (BPC)',
            'account_number' => '0010.0000.987654321.10',
            'iban' => 'AO06.0010.0000.9876.5432.1010.5',
            'invoice_reference' => 'FAT-2026-HDB-084',
            'authorized_amount' => 3500000.00,
            'private_notes' => 'Pagamento direto ao hospital mediante apresentação de fatura proforma confirmada pela administração hospitalar.',
        ]);

        // Payment destination record for Campaign 2
        PaymentDestination::create([
            'campaign_id' => $campaign2->id,
            'destination_type' => 'beneficiary_transfer',
            'institution_or_payee_name' => 'João Domingos (Beneficiário Verificado)',
            'bank_name' => 'Banco BAI',
            'account_number' => '0040.0000.554433221.10',
            'iban' => 'AO06.0040.0000.5544.3322.1010.1',
            'mobile_money_number' => '+244924333444',
            'authorized_amount' => 2000000.00,
            'private_notes' => 'Transferência autorizada após validação presencial e comprovativo de receita em farmácia especializada.',
        ]);

        // Audit Logs
        AuditLog::log(
            action: 'campaign_created',
            entityType: Campaign::class,
            entityId: $campaign1->id,
            newValues: ['title' => $campaign1->title, 'target' => 3500000]
        );

        AuditLog::log(
            action: 'campaign_approved',
            entityType: Campaign::class,
            entityId: $campaign1->id,
            newValues: ['status' => 'approved', 'destination' => 'hospital_direct']
        );
    }
}
