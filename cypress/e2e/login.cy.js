describe('Formulaire de Connexion', () => {
  it('test 1 - connexion OK', () => {
    cy.visit('http://localhost:8088/S6-Webapp/public/index.php/login');
  
    cy.get('input[name="_username"]').type('test.test@test.fr');
    cy.get('input[name="_password"]').type('test');
  
    cy.get('button[type="submit"]').click();
  
    cy.url().should('not.include', '/login');
    cy.contains('You are logged in as test.test@test.fr').should('exist');
  
    cy.get('input[name="_username"]').clear();
    cy.get('input[name="_password"]').clear();
  });


  it('test 2 - connexion KO', () => {
    cy.visit('http://localhost:8088/S6-Webapp/public/index.php/login');

    cy.get('input[name="_username"]').type('wrong.username');
    cy.get('input[name="_password"]').type('wrongpassword');

    cy.get('button[type="submit"]').click();

    cy.contains('Invalid credentials.').should('exist');
  });
});
