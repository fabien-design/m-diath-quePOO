class TableSorter {
    constructor(tableBodySelector, tableHeaderSelector) {
        this.tableBody = document.querySelector(tableBodySelector);
        this.tableContents = this.tableBody.querySelectorAll("tr");
        this.tableHeader = document.querySelector(tableHeaderSelector);
        this.headerColumns = this.tableHeader.querySelectorAll("th");
        this.headerColumnsName = Array.from(this.headerColumns).map(
            (col) => col.innerText
        );
        this.arrayContents = [];

        this.initTableData();
        this.initHeaderClickEvents();
    }

    sortByKey(array, key, order) {
        return array.sort((a, b) => {
            const val1 = a[key].text.toLowerCase ? a[key].text.toLowerCase() : a[key].text;
            const val2 = b[key].text.toLowerCase ? b[key].text.toLowerCase() : b[key].text;

            if (order === "ASC") {
                return val1 < val2 ? -1 : val1 > val2 ? 1 : 0;
            } else {
                return val1 > val2 ? -1 : val1 < val2 ? 1 : 0;
            }
        });
    }

    initTableData() {
        this.tableContents.forEach((row) => {
            const rowColumns = row.querySelectorAll("td");
            let rowContent = {};

            Array.from(rowColumns).forEach((col, idx) => {
                rowContent[this.headerColumnsName[idx]] = {
                    html: col.innerHTML,
                    text: col.textContent.trim()
                };
            });

            this.arrayContents.push(rowContent);
        });
    }

    initHeaderClickEvents() {
        this.headerColumns.forEach((col, idx) => {
            col.addEventListener("click", () => {
                this.headerColumns.forEach(
                    (col) =>
                        (col.innerText = col.innerText
                            .replace("↓", "")
                            .replace("↑", ""))
                );

                let colText = col.innerText;
                let order = "ASC";
                if (col.dataset.order === "DESC" || !col.dataset.order) {
                    let defaultText = colText.replace("↓", "").replace("↑", "");
                    col.innerText = "↑ " + defaultText;
                    col.dataset.order = "ASC";
                    order = "ASC";
                } else if (col.dataset.order === "ASC") {
                    let defaultText = colText.replace("↑", "").replace("↓", "");
                    col.innerText = "↓ " + defaultText;
                    col.dataset.order = "DESC";
                    order = "DESC";
                }

                this.arrayContents = this.sortByKey(
                    // Utilisation de sanitizeHTML pour nettoyer le HTML
                    this.arrayContents,
                    this.headerColumnsName[idx],
                    order
                );
                this.updateTable();
            });
        });
    }

    sanitizeHTML(html) {
        const tempDiv = document.createElement("div");
        tempDiv.innerHTML = html;

        const allowedTags = ["A", "STRONG", "EM"];
        
        // Parcours des nœuds enfants
        tempDiv.querySelectorAll("*").forEach((node) => {
            if (!allowedTags.includes(node.nodeName)) {
                // Remplacement des balises non autorisées par leur contenu textuel
                const textNode = document.createTextNode(node.textContent);
                node.parentNode.replaceChild(textNode, node);
            }
        });

        return tempDiv.innerHTML;
    }

    updateTable() {
        this.tableBody.innerHTML = "";
        this.arrayContents.forEach((row) => {
            const tr = document.createElement("tr");
            this.tableBody.appendChild(tr);

            Object.values(row).forEach((cell) => {
                const td = document.createElement("td");
                td.innerHTML = this.sanitizeHTML(cell.html);
                tr.appendChild(td);
            });
        });
    }
}

const sorter = new TableSorter("tbody", "thead");
