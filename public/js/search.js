function searchDropdown() {
    return {
        isOpen: false,
        query: '',
        suggestions: ['esai kehidupan', 'esai filsafat', 'renungan acak', 'observasi sosial'],
        get filtered() {
            return this.suggestions.filter(s =>
                s.toLowerCase().includes(this.query.toLowerCase())
            );
        },
        highlight(text) {
            if (!this.query) return text;
            return text.replace(new RegExp(this.query, 'gi'), m => `<span class="bg-yellow-100 font-bold">${m}</span>`);
        },
        open() { this.isOpen = true; },
        close() { this.isOpen = false; }
    }
}



