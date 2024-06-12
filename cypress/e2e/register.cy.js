describe('Création de compte', () => {
  it('crée un nouveau compte avec succès', () => {
      cy.visit('http://localhost:8088/S6-Webapp/public/index.php/register');

      cy.get('input[name="registration_form[email]"]').type('test@example.com');
      cy.get('input[name="registration_form[lastname]"]').type('Doe');
      cy.get('input[name="registration_form[firstname]"]').type('John');
      cy.get('input[name="registration_form[plainPassword]"]').type('password');
      cy.get('input[name="registration_form[agreeTerms]').check();

      cy.get('button[type="submit"]').click();

      cy.url().should('include', '/login');
  });

  it('affiche une erreur si le formulaire est soumis avec des informations incorrectes', () => {
      cy.visit('http://localhost:8088/S6-Webapp/public/index.php/register');

      cy.get('input[name="registration_form[email]"]').type('test@example.com');
      cy.get('input[name="registration_form[lastname]"]').type('Doe');
      cy.get('input[name="registration_form[firstname]"]').type('John');
      cy.get('input[name="registration_form[plainPassword]"]').type('pass');
      cy.get('input[name="registration_form[agreeTerms]').check();

      cy.get('button[type="submit"]').click();

      cy.contains('Your password should be at least 6 characters').should('exist');
  });
});
