<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/png" href="/favicon.png?v=3">
<link rel="icon" type="image/x-icon" href="/favicon.ico?v=3">
<title>Sobre nós - NEXUS HelpDesk</title>
<link rel="stylesheet" href="style.css">
<style>
body{margin:0;font-family:Inter,system-ui;background:#FAFAF8;display:flex;flex-direction:column;min-height:100vh;color:#37352F}
.header{background:#FBFBFA;border-bottom:1px solid #EAE9E5;padding:16px 24px;display:flex;align-items:center;gap:12px;position:sticky;top:0;z-index:10}
.logo{background:#111;width:36px;height:36px;border-radius:8px;display:grid;place-items:center;font-weight:700;font-size:18px;color:#fff}
.header h1{margin:0;font-size:16px;font-weight:600;color:#111} .header h1 span{color:#706F6B;font-weight:400}
.header p{margin:0;font-size:10px;letter-spacing:1.2px;opacity:.6;color:#706F6B}
.nav{margin-left:auto;display:flex;gap:16px;font-size:13px}
.nav a{color:#706F6B;text-decoration:none} .nav a.active{color:#111;font-weight:600;border-bottom:2px solid #111;padding-bottom:2px}
.container{max-width:780px;margin:32px auto;padding:0 20px;flex:1;width:100%;box-sizing:border-box}
.card{background:#FFFFFF;border:1px solid #EAE9E5;border-radius:16px;padding:28px;box-shadow:0 4px 24px rgba(0,0,0,0.04);margin-bottom:16px}
.card h2{margin:0;color:#111;font-size:24px;font-weight:600;letter-spacing:-0.3px}
.sub{color:#706F6B;margin:6px 0 0;font-size:14px}
.titulo{font-size:15px;font-weight:600;text-align:left;margin:32px 4px 12px;color:#111;letter-spacing:-0.2px;text-transform:uppercase;opacity:.8}
.step{display:flex;gap:14px;align-items:flex-start}
.icon{width:40px;height:40px;border-radius:8px;background:#F1F1EF;color:#111;display:grid;place-items:center;font-size:18px;flex-shrink:0;border:1px solid #EAE9E5}
.card p{margin:8px 0 0;color:#37352F;font-size:14.5px;line-height:1.7}
.card b{color:#111}
.btn{background:#111;color:white;width:100%;padding:14px;border:0;border-radius:10px;font-weight:600;cursor:pointer;margin-top:10px;font-size:14px}
.btn:hover{background:#000}
footer{background:#FBFBFA;border-top:1px solid #EAE9E5;color:#706F6B;padding:24px;text-align:center;font-size:12.5px}
footer a{color:#37352F;text-decoration:none;margin:0 8px}
.perfil{display:flex;gap:12px;align-items:center;background:#FBFBFA;border:1px solid #EAE9E5;padding:14px;border-radius:12px;margin-top:16px}
.perfil strong{color:#111}
</style>
</head>
<body>
<div class="header">
  <div class="logo">N</div>
  <div><h1>NEXUS <span>HelpDesk</span></h1><p>PONTO DE CONEXÃO • SUPORTE PROFISSIONAL</p></div>
  <div class="nav"><a href="index.php">Login</a><a href="sobre.php" class="active">Sobre</a><a href="termos.php">Termos</a><a href="privacidade.php">Privacidade</a></div>
</div>

<div class="container">
  <div class="card">
    <h2>Sobre a NEXUS</h2>
    <p class="sub">Ponto de conexão entre o problema e a solução</p>
    <p>O NEXUS nasceu da necessidade real que vi em empresas de Osasco e região: o suporte desorganizado via WhatsApp, e-mail e planilhas. Decidi criar uma plataforma simples, rápida e profissional para organizar tudo em um só lugar.</p>
    <div class="perfil">
      <div class="logo" style="width:36px;height:36px">G</div>
      <div>
        <strong>Guilherme Medeiros Oliveira</strong><br>
        <span style="font-size:13px;color:#37352F">Estudante e Técnico de TI | HelpDesk N1 | Criador do NEXUS</span><br>
        <span style="font-size:12px;color:#706F6B">medeiros.oliveira.guilherme@gmail.com • Osasco/SP</span>
      </div>
    </div>
  </div>

  <h3 class="titulo">Nossa Missão</h3>
  <div class="card">
    <p>Conectar clientes e técnicos de forma rápida, transparente e eficiente. Transformar o suporte em uma experiência ágil, confiável e humana, garantindo que cada problema seja resolvido com profissionalismo e excelência. Nosso SLA de demonstração é de <b>4h úteis</b>.</p>
  </div>

  <h3 class="titulo">Como funciona</h3>
  <div class="card"><div class="step"><div class="icon">💬</div><div><b>1. Cliente abre chamado</b><p>Abra seu chamado descrevendo o problema em segundos, pelo portal. Nosso sistema gera um protocolo automático (NX-000...) e categoriza por setor e prioridade.</p></div></div></div>
  <div class="card"><div class="step"><div class="icon">🛠️</div><div><b>2. Técnico resolve</b><p>Especialistas qualificados atendem sua solicitação com transparência, atualizando o status em tempo real e conversando via chat interno até a resolução.</p></div></div></div>
  <div class="card"><div class="step"><div class="icon">📊</div><div><b>3. Gestor acompanha</b><p>Acompanhe métricas, tempo médio de resposta, volume de chamados e SLA em painel completo para garantir controle e qualidade do serviço prestado.</p></div></div></div>

  <h3 class="titulo">Nossos Valores</h3>
  <div class="card"><b>🔒 Segurança e transparência em cada atendimento.</b><p>Estamos ao seu lado para resolver com agilidade, cuidado e proteção dos seus dados conforme LGPD.</p></div>
  <div class="card"><b>🤝 Parceria</b><p>Tecnologia e processos modernos para evoluir seu suporte. Crescemos juntos com nossos clientes e colaboradores.</p></div>

  <h3 class="titulo">Tecnologia</h3>
  <div class="card"><b>💻 Infraestrutura Moderna</b><p>Plataforma 100% em nuvem, segura e escalável, desenvolvida com PHP, MySQL, JavaScript, HTML5 e CSS3. Dados criptografados e monitoramento para alta disponibilidade.</p><p style="margin-top:10px"><b>Stack:</b> PHP • MySQL • JavaScript • HTML5 • CSS3 • Vercel • GitHub</p></div>

  <button class="btn" onclick="location.href='mailto:medeiros.oliveira.guilherme@gmail.com'">Fale com nosso time → medeiros.oliveira.guilherme@gmail.com</button>
</div>

<footer>
  <a href="sobre.php">Sobre nós</a> | <a href="termos.php">Termos de Uso</a> | <a href="privacidade.php">Privacidade (LGPD)</a> | <a href="mailto:medeiros.oliveira.guilherme@gmail.com">Contato</a><br><br>
  © 2026 NEXUS - HelpDesk • Desenvolvido por Guilherme Medeiros Oliveira • HelpDesk N1 • Osasco/SP
</footer>
</body>
</html>