markdown<div align="center">

<img src="logo-nexus.png" width="90" style="border-radius:18px" />

# NEXUS - HelpDesk
### Ponto de Conexão entre o Problema e a Solução

<img src="https://readme-typing-svg.herokuapp.com?font=Inter&weight=800&size=22&duration=3000&pause=1000&color=2563EB&center=true&vCenter=true&width=600&lines=SLA+4h+%7C+Suporte+N1%2FN2+%7C+Protocolo+NX;Chat+em+Tempo+Real+%2B+Hist%C3%B3rico+Completo;Sistema+Enterprise+para+Portf%C3%B3lio" />

[![PHP](https://img.shields.io/badge/PHP-8.2-2563eb?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-0f172a?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Status](https://img.shields.io/badge/Status-Ativo-22c55e?style=for-the-badge)](https://guilhermemedeiros.freedev.app)
[![NEXUS](https://img.shields.io/badge/NEXUS-HelpDesk-60a5fa?style=for-the-badge)]()

**[🔗 DEMO AO VIVO](https://guilhermemedeiros.freedev.app) • [📸 Screenshots](#-preview) • [🚀 Funcionalidades](#-funcionalidades-premium)**

</div>

---

## 💡 Por que NEXUS?

> **NEXUS** vem do latim: *ponto central onde tudo se conecta*. 
> É um termo usado em infraestrutura de redes para definir o ponto de interconexão. Meu sistema é exatamente isso: **o elo que conecta o problema do usuário à solução técnica, com protocolo NX, chat e SLA.**

---

## 👀 Preview

<div align="center">

| Dashboard Cliente | Painel Admin NEXUS |
| :---: | :---: |
| ![Dashboard Cliente](dashboard.png) | ![Painel Admin](admin.png) |
| *Cards com prioridade colorida* | *Gerenciamento com protocolo NX-000001* |

<img src="https://user-images.githubusercontent.com/73097560/115834477-dbab4500-a447-11eb-8c61-5e1fc0d8b1e1.gif" width="100%">

</div>

## 🚀 Funcionalidades Premium

### 🔐 Autenticação Segura NEXUS
- Login com `password_hash` + compatibilidade automática `md5 -> bcrypt`
- Proteção com `prepared statements` contra SQL Injection
- Sessão com controle de permissão (admin só vê tudo, usuário só vê seus chamados)

### 🎫 Gestão de Chamados Enterprise
- **Protocolo NEXUS:** Todo chamado gera `NX-000001`, `NX-000002`...
- Categorias: Suporte Técnico, Rede, Software, Hardware, E-mail, Impressora
- Prioridades com badge colorido: 🟢 Baixa | 🟡 Média | 🔴 Alta
- Status: Aberto / Em Andamento / Fechado

### 💬 Chat em Tempo Real
- Conversa estilo WhatsApp dentro do chamado
- Mensagens diferenciadas: Admin (azul degradê) vs Usuário (branco)
- Histórico completo salvo no banco `chamado_mensagens`
- Auto-scroll para última mensagem

### 🛠️ Painel Admin Profissional
- Visualizar todos os chamados com nome do solicitante
- Atualizar status com 1 clique
- Excluir chamados com confirmação (cascade delete)
- Design responsivo 100% mobile

### 🎨 UI/UX Profissional
- Marca fictícia **NEXUS - HelpDesk** com logo N estilizada (degradê + sombra)
- Header sticky com separador `|` entre links
- Design System com variáveis CSS `:root` e animações suaves
- 100% responsivo com grid adaptativo

---

## 🛠️ Stack

<div align="center">

| Camada | Tecnologias |
| :--- | :--- |
| **Backend** | PHP 8.2 • MySQL 8 • MySQLi Prepared Statements |
| **Frontend** | HTML5 • CSS3 Moderno (Flex/Grid) • Inter Font • Animações |
| **Segurança** | Bcrypt • MD5 Migration • Session Control |
| **Infra** | InfinityFree • Git • Protocolo NX |

</div>

---

## 🔑 Acesso de Teste

<div align="center">

| Perfil | Email | Senha | Acesso |
| :---: | :---: | :---: | :---: |
| **👨‍💻 Admin (Técnico N2)** | `admin@nexus.com` | `admin123` | Vê todos os chamados, responde e exclui |
| **👤 Usuário (Cliente)** | `guilherme@teste.com` | `123` | Abre e acompanha seus chamados |

</div>

> **Fluxo de teste:** 1. Logue como cliente > Abra chamado "PC sem internet" > Saia > Logue como admin > Responda no chat > Volte como cliente e veja a resposta.

---

## 📸 Como usar

```bash
# 1. Clone o repositório
git clone https://github.com/seu-usuario/nexus-helpdesk.git

# 2. Importe o banco (helpdesk.sql)
# 3. Configure o config.php com seu MySQL
# 4. Acesse index.php

# Estrutura do Banco:
# usuarios (id, nome, email, senha, tipo)
# chamados (id, usuario_id, titulo, descricao, categoria, prioridade, status, data_abertura)
# chamado_mensagens (id, chamado_id, usuario_id, mensagem, data_envio)👨‍💻 Autor
<div align="center">

Guilherme Medeiros
Estudante de Análise e Desenvolvimento de Sistemas | Em busca de vaga Help Desk / Suporte N1/N2
LinkedIn
Portfolio
Email
NEXUS = Ponto de conexão entre o problema e a solução. Meu código conecta usuário à resolução com SLA, histórico e experiência profissional.
<img src="https://user-images.githubusercontent.com/73097560/115834477-dbab4500-a447-11eb-8c61-5e1fc0d8b1e1.gif" width="100%">

⭐ Se esse projeto te ajudou, deixa uma estrela!
</div>
