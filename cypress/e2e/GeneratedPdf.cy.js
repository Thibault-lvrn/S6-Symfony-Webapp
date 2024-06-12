describe('GeneratePdfType form', () => {
    beforeEach(() => {
        cy.visit('http://localhost:8088/S6-Webapp/public/index.php/login');
        cy.get('input[name="_username"]').type('test.test@test.fr');
        cy.get('input[name="_password"]').type('test');
        cy.get('form').submit();
        cy.url().should('not.include', '/login');
        cy.visit('http://localhost:8088/S6-Webapp/public/index.php/generate/pdf');
    });

    it('remplit et soumet le formulaire', () => {
        cy.get('form.convertisseur__form').as('generatePdfForm');
        cy.get('@generatePdfForm')
            .find('input[name="url"]')
            .type('https://google.com');
        cy.get('@generatePdfForm')
            .find('input[name="fileName"]')
            .type('exemple');
        cy.get('@generatePdfForm').submit();
        cy.url().should('include', '/generate/pdf/success');

        cy.get('.pdf-path').should('exist').and('contain.text', 'http://localhost:8088/S6-Webapp/public/pdf/exemple.pdf');

        cy.get('.pdf-generated-count').should('exist').and('contain.text', '1');
    });

    it('affiche une erreur si le nombre limite de PDFs est atteint', () => {
        cy.get('form.convertisseur__form').as('generatePdfForm');
        cy.get('@generatePdfForm')
            .find('input[name="url"]')
            .type('https://google.com');
        cy.get('@generatePdfForm')
            .find('input[name="fileName"]')
            .type('exemple');

        cy.intercept('POST', '/generate/pdf/submit', (req) => {
            req.reply({
                statusCode: 200,
                body: '<h1>limite de PDFs atteinte</h1>'
            });
        });

        cy.get('@generatePdfForm').submit();
        cy.get('h1').should('contain.text', 'limite de PDFs atteinte');
    });

    it('affiche une erreur si le nombre limite de PDFs est atteint', () => {
        cy.intercept('POST', '/generate/pdf/submit', (req) => {
            req.reply({
                statusCode: 200,
                body: '<h1>limite de PDFs atteinte</h1>'
            });
        });
    
        cy.get('form.convertisseur__form').as('generatePdfForm');
        cy.get('@generatePdfForm')
            .find('input[name="url"]')
            .type('https://google.com');
        cy.get('@generatePdfForm')
            .find('input[name="fileName"]')
            .type('exemple');
    
        cy.get('@generatePdfForm').submit();
    
        cy.url().should('include', '/generate/pdf/success');
    
        cy.get('h1').should('contain.text', 'limite de PDFs atteinte');
    });
});