<?php

namespace Adminer;

/**
 * Class responsible for exporting and copying table structures to various formats
 * (Markdown, JSON, Text, CSV, and SQL).
 * Includes methods to generate a toolbar for format selection, extract table data,
 * and perform operations like copying formatted data to the clipboard.
 *
 * @author Marcus Nightingale
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 */
class CopyStructureExport
{
    public function head()
    {
        echo <<<HTML
<style>
    #sb-structure-copy {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: .65rem;
        margin: .5rem 0 .75rem;
        font: inherit;
    }
    #sb-structure-copy .sb-structure-copy-label {
        color: inherit;
        opacity: .75;
    }
    #sb-structure-copy button {
        padding: 0;
        border: 0;
        background: none;
        color: blue;
        cursor: pointer;
        font: inherit;
        text-decoration: none;
    }
    #sb-structure-copy button:hover,
    #sb-structure-copy button:focus {
        color: red;
        text-decoration: underline;
    }
    #sb-structure-copy button + button {
        margin-left: .05rem;
    }
    #sb-structure-copy .sb-structure-copy-status {
        margin-left: .25rem;
        color: #1C8439;
        font-weight: 400;
        min-width: 6rem;
    }
</style>
HTML;

        echo '<script type="text/javascript" ' . nonce() . '>' . <<<'HTML'
(function () {
    function normalize(value) {
        return String(value || '').replace(/\s+/g, ' ').trim();
    }

    function escapeMarkdownCell(value) {
        return normalize(value)
            .replace(/\\/g, '\\\\')
            .replace(/\|/g, '\\|')
            .replace(/\r?\n/g, '<br>');
    }

    function escapeCsvCell(value) {
        return '"' + normalize(value).replace(/"/g, '""') + '"';
    }

    function escapeSqlIdentifier(value) {
        return '`' + normalize(value).replace(/`/g, '``') + '`';
    }

    function escapeSqlString(value) {
        return "'" + normalize(value).replace(/\\/g, '\\\\').replace(/'/g, "''") + "'";
    }

    function toMarkdown(rows) {
        var lines = ['| Column | Type | Comment |', '| --- | --- | --- |'];
        rows.forEach(function (row) {
            lines.push('| ' + escapeMarkdownCell(row.column) + ' | ' + escapeMarkdownCell(row.type) + ' | ' + escapeMarkdownCell(row.comment) + ' |');
        });
        return lines.join('\n');
    }

    function toText(rows) {
        var lines = ['Column\tType\tComment'];
        rows.forEach(function (row) {
            lines.push([normalize(row.column), normalize(row.type), normalize(row.comment)].join('\t'));
        });
        return lines.join('\n');
    }

    function toCsv(rows) {
        var lines = ['"Column","Type","Comment"'];
        rows.forEach(function (row) {
            lines.push([escapeCsvCell(row.column), escapeCsvCell(row.type), escapeCsvCell(row.comment)].join(','));
        });
        return lines.join('\n');
    }

    function toJson(rows) {
        return JSON.stringify(rows, null, 2);
    }

    function getTableName() {
        var params = new URL(window.location.href).searchParams;
        var table = params.get('table');
        if (table) {
            return table;
        }

        return 'table';
    }

    function toSql(rows) {
        var lines = ['CREATE TABLE ' + escapeSqlIdentifier(getTableName()) + ' ('];

        rows.forEach(function (row, index) {
            var definition = '  ' + escapeSqlIdentifier(row.column) + ' ' + normalize(row.sqlType || row.type);
            if (row.comment) {
                definition += ' COMMENT ' + escapeSqlString(row.comment);
            }
            lines.push(definition + (index < rows.length - 1 ? ',' : ''));
        });

        lines.push(');');
        return lines.join('\n');
    }

    function formatRows(rows, format) {
        switch (format) {
            case 'markdown':
                return toMarkdown(rows);
            case 'json':
                return toJson(rows);
            case 'sql':
                return toSql(rows);
            case 'csv':
                return toCsv(rows);
            case 'text':
            default:
                return toText(rows);
        }
    }

    function getRows(table) {
        var source = table.tBodies && table.tBodies.length ? table.tBodies[0] : table;
        var rows = [];

        Array.prototype.forEach.call(source.rows || [], function (tr) {
            if (!tr.cells || tr.cells.length < 2) {
                return;
            }

            var first = normalize(tr.cells[0].textContent);
            var second = normalize(tr.cells[1].textContent);
            var typeSpan = tr.cells[1].querySelector('span');

            if (first === 'Column' && second === 'Type') {
                return;
            }

            rows.push({
                column: first,
                type: second,
                sqlType: typeSpan ? normalize(typeSpan.textContent) : second,
                comment: tr.cells[2] ? normalize(tr.cells[2].textContent) : ''
            });
        });

        return rows;
    }

    function findStructureTable() {
        var tables = document.querySelectorAll('table.nowrap.odds, table.odds');

        for (var i = 0; i < tables.length; i++) {
            var table = tables[i];
            var header = table.querySelector('thead tr');
            if (!header) {
                continue;
            }

            var cells = header.querySelectorAll('th, td');
            if (cells.length < 2) {
                continue;
            }

            if (normalize(cells[0].textContent) === 'Column' && normalize(cells[1].textContent) === 'Type') {
                return table;
            }
        }

        return null;
    }

    function copyText(text, status) {
        var done = function () {
            if (status) {
                status.textContent = '✓ Copied';
                window.setTimeout(function () {
                    status.textContent = '';
                }, 1200);
            }
        };

        var fail = function () {
            if (status) {
                status.textContent = 'Copy failed';
                window.setTimeout(function () {
                    status.textContent = '';
                }, 1600);
            }
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(done).catch(function () {
                fail();
            });
            return;
        }

        try {
            var textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.setAttribute('readonly', 'readonly');
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            textarea.style.top = '0';
            document.body.appendChild(textarea);
            textarea.select();
            textarea.setSelectionRange(0, textarea.value.length);
            var ok = document.execCommand('copy');
            document.body.removeChild(textarea);
            if (ok) {
                done();
            } else {
                fail();
            }
        } catch (error) {
            fail();
        }
    }

    function ensureToolbar(table) {
        var existing = document.getElementById('sb-structure-copy');
        if (existing) {
            return existing;
        }

        var toolbar = document.createElement('div');
        toolbar.id = 'sb-structure-copy';

        var label = document.createElement('span');
        label.className = 'sb-structure-copy-label';
        label.textContent = 'Copy table as';
        toolbar.appendChild(label);

        var status = document.createElement('span');
        status.className = 'sb-structure-copy-status';
        status.setAttribute('aria-live', 'polite');
        toolbar.appendChild(status);

        ['markdown', 'json', 'text', 'csv', 'sql'].forEach(function (format) {
            var button = document.createElement('button');
            button.type = 'button';
            button.textContent = format;
            button.addEventListener('click', function () {
                var rows = getRows(table);
                copyText(formatRows(rows, format), status);
            });
            toolbar.insertBefore(button, status);
        });

        var container = table.closest('.scrollable');
        if (container && container.parentNode) {
            if (container.nextSibling) {
                container.parentNode.insertBefore(toolbar, container.nextSibling);
            } else {
                container.parentNode.appendChild(toolbar);
            }
        } else if (table.parentNode) {
            if (table.nextSibling) {
                table.parentNode.insertBefore(toolbar, table.nextSibling);
            } else {
                table.parentNode.appendChild(toolbar);
            }
        }

        return toolbar;
    }

    function init() {
        var table = findStructureTable();
        if (!table) {
            return;
        }

        ensureToolbar(table);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
HTML;
    }
}
