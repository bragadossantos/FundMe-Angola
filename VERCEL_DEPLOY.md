# Publicar a FundMe Angola no Vercel

Vercel corre PHP como funções serverless (via o runtime comunitário
`vercel-php`), não como um servidor tradicional. Isso muda três coisas em
relação ao Docker/Render:

1. **Não há disco persistente.** `/tmp` é escrevível mas é apagado entre
   invocações — não serve para guardar imagens de campanhas ou documentos
   privados. Estes têm de ficar num bucket compatível com S3.
2. **Não há base de dados embutida.** É preciso uma base de dados gerida
   externa (Postgres/MySQL) acessível pela internet.
3. **Não há shell.** As migrations não correm automaticamente como no
   `docker-entrypoint.sh` — há um endpoint dedicado para isso (abaixo).

Este projeto já está preparado para isto (`api/index.php`,
`config/filesystems.php`, `vercel.json`), mas os serviços externos
(base de dados e bucket) têm de ser criados por ti — só tu tens acesso à
tua conta Vercel/AWS/Cloudflare.

## 1. Criar uma base de dados gerida

Qualquer um destes serve (têm planos gratuitos): **Neon** ou **Supabase**
(Postgres), **PlanetScale** (MySQL), ou o próprio **Vercel Postgres** /
**Vercel Storage → Neon**. Depois de criar, guarda a *connection string*
completa (algo como `postgres://user:pass@host:5432/dbname?sslmode=require`).

## 2. Criar um bucket compatível com S3

Recomendado: **Cloudflare R2** (sem custos de egress, plano gratuito
generoso) ou AWS S3. Cria **dois buckets** (ou um só com dois prefixos, ver
abaixo) — um para conteúdo público (imagens de campanhas) e outro para
documentos privados (BI, relatórios médicos, comprovativos):

- `fundmeangola-public` — precisa de acesso de leitura pública (política do
  bucket) para servir as imagens diretamente.
- `fundmeangola-private` — mantém-se privado; os ficheiros só são servidos
  através da aplicação (`DocumentController`), nunca diretamente do bucket.

Se preferires um único bucket, define `AWS_BUCKET` e deixa
`AWS_BUCKET_PUBLIC`/`AWS_BUCKET_PRIVATE` por definir — o código usa
`AWS_BUCKET` como fallback e separa o conteúdo por prefixo (`public/` vs
`private/`) dentro dele.

## 3. Variáveis de ambiente no painel do Vercel

O `vercel.json` já define os valores não-secretos (`APP_ENV`,
`SESSION_DRIVER`, drivers de storage, etc.) — não precisas de repetir esses.
No painel do projeto → **Settings → Environment Variables**, define apenas
os segredos (nunca vão para `vercel.json`, que é público no repositório):

| Variável | Valor |
|---|---|
| `APP_KEY` | gera localmente com `php artisan key:generate --show` |
| `APP_URL` | o domínio final (ex: `https://fundmeangola.vercel.app`) |
| `DB_CONNECTION` | `pgsql` (ou `mysql`, conforme o passo 1) |
| `DB_URL` | a connection string completa do passo 1 |
| `AWS_ACCESS_KEY_ID` | credencial do bucket |
| `AWS_SECRET_ACCESS_KEY` | credencial do bucket |
| `AWS_DEFAULT_REGION` | região do bucket (R2: `auto`) |
| `AWS_ENDPOINT` | endpoint do R2/S3 (não aplicável na AWS "normal") |
| `AWS_BUCKET_PUBLIC` | nome do bucket público |
| `AWS_BUCKET_PRIVATE` | nome do bucket privado |
| `AWS_URL` | URL pública base do bucket público (para servir as imagens) |
| `DEPLOY_MIGRATE_TOKEN` | uma string aleatória longa e só tua — ver passo 4 |
| `MAIL_MAILER` / credenciais SMTP | opcional, para o reset de password funcionar de verdade (ver nota abaixo) |

## 4. Correr as migrations após cada deploy

Como não há shell, existe a rota `GET /deploy/migrate?token=SEU_TOKEN`
(protegida pelo `DEPLOY_MIGRATE_TOKEN` que definiste acima — sem essa
variável definida, a rota recusa sempre com 403). Depois de cada deploy que
altere o esquema da base de dados:

```
curl "https://o-teu-dominio.vercel.app/deploy/migrate?token=SEU_TOKEN"
```

Para também semear os dados de demonstração na primeira vez, acrescenta
`&seed=1`. Tal como no Render, em `APP_ENV=production` a password gerada
para as contas de demonstração aparece **uma única vez** na resposta —
guarda-a imediatamente.

## 5. Publicar

Liga o repositório GitHub ao Vercel normalmente (Import Project). O Vercel
deteta o `vercel.json` e usa o runtime `vercel-php`, que corre
`composer install` automaticamente durante o build (por isso `/vendor` não
está no repositório). Depois do primeiro deploy, corre o passo 4.

## O que não funciona (ou funciona de forma limitada) no Vercel

- **Filas (queues):** ficam sempre síncronas (`QUEUE_CONNECTION=sync`) —
  não há workers persistentes.
- **Envio de email real:** `MAIL_MAILER=log` por defeito, tal como no
  Render — para o reset de password enviar emails a sério, configura um
  provedor por API (Resend, Postmark, Mailgun) nas variáveis de ambiente.
- **Sessões:** usam cookies (`SESSION_DRIVER=cookie`), cifradas e só
  enviadas por HTTPS. Isto tem um limite de ~4KB por cookie, mais do que
  suficiente para o uso desta aplicação, mas não guardes dados grandes na
  sessão.
