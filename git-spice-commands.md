# Git-Spice - Lista de Comandos

## Visão geral / flags globais

- `gs <command> [flags]` — formato geral de invocação.
- `-h, --help` — mostra ajuda para o comando.
- `--version` — exibe a versão do git-spice.
- `-v, --verbose` — habilita saída detalhada (verbose).
- `-C, --dir=DIR` — muda para o diretório `DIR` antes de executar a ação.
- `--[no-]prompt` — ativa ou desativa prompts para informações faltantes.

## Autocompletar

- `gs shell completion [<shell>]` — gera o script de autocompletar para o shell (bash, zsh, fish).

## Autenticação (auth)

- `gs auth login [flags]` — realiza login em um serviço (GitHub, GitLab).
- `gs auth status [flags]` — mostra o status atual de autenticação.
- `gs auth logout [flags]` — desconecta do serviço.

## Repositório (repo)

- `gs repo init [flags]` — inicializa o repositório para uso com git-spice.
- `gs repo sync [flags]` — sincroniza repositório com o remoto (pull + limpeza de branches mescladas).
- `gs repo restack` — reaplica (rebase) todas as branches rastreadas.

## Log

- `gs log short [flags]` — lista branches (“curto”), focando na pilha relativa ao branch atual.
- `gs log long [flags]` — mostra informação detalhada (branches + commits).

## Stack (pilha de branches)

- `gs stack submit [flags]` — envia toda a pilha de branches como PR/MR.
- `gs stack restack [flags]` — reaplica rebases em todos os branches da pilha.
- `gs stack edit [flags]` — edita a ordem dos branches em uma pilha.
- `gs stack delete [flags]` — deleta todas as branches de uma pilha.

- `gs upstack submit [flags]` — envia o branch atual e todos os que estão acima.
- `gs upstack delete [flags]` — deleta todos os branches acima do atual.
- `gs downstack submit [flags]` — envia o branch atual e todos os abaixo até o trunk.
- `gs downstack edit [flags]` — edita a ordem dos branches abaixo.

## Branch

- `gs branch track [<branch>] [flags]` — passa a rastrear um branch.
- `gs branch untrack [<branch>]` — remove o tracking de um branch.
- `gs branch submit [flags]` — envia somente um branch específico.
- `gs commit amend [flags]` — modifica (amenda) o commit atual.
- `gs commit fixup [<commit>] [flags]` — aplica mudanças a um commit anterior da pilha.

## Rebase

- `gs rebase continue [flags]` — continua uma operação interrompida de rebase.
- `gs rebase abort` — aborta a operação de rebase em progresso.

## Navegação entre branches

- `gs up [<n>] [flags]` — sobe para o branch acima na pilha.
- `gs down [<n>] [flags]` — desce para o branch abaixo na pilha.
- `gs top [flags]` — vai para o topo da pilha (branch mais acima).
