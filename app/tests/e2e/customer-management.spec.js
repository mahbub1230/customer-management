// tests/e2e/customer-management.spec.js
import { test, expect } from '@playwright/test';

const baseUrl = 'https://demo.slipstram.com/';
const customerName = 'Test Customer ' + Date.now();
const updatedCustomerName = customerName + ' Updated';

let customerReference = 'REF-' + Date.now();
let customerId;

test.describe('Customer Management', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto(baseUrl+'customers');
    await page.waitForSelector('text=Customers');
  });

  test('should create and update a customer', async ({ page }) => {
    await page.getByText('Create', { exact: true }).click();

    await page.getByLabel('Name').fill(customerName);
    await page.getByLabel('Reference').fill(customerReference);
    await page.getByLabel('Category').selectOption('Gold');

    await page.getByRole('button', { name: 'Save' }).click();
    let message = await page.getByTestId('customer-success-message').textContent();
    expect(['Customer created successfully', 'Customer updated successfully']).toContain(message.trim());

    await page.getByLabel('Name').fill(updatedCustomerName);
    await page.getByRole('button', { name: 'Save' }).click();
    message = await page.getByTestId('customer-success-message').textContent();
    expect(['Customer created successfully', 'Customer updated successfully']).toContain(message.trim());
  });

  test('should create, update and delete a contact', async ({ page }) => {
    await page.getByText('Create', { exact: true }).click();

    await page.getByLabel('Name').fill(customerName);
    customerReference = 'REF-' + Date.now();
    await page.getByLabel('Reference').fill(customerReference);
    await page.getByLabel('Category').selectOption('Silver');
    await page.getByRole('button', { name: 'Save' }).click();
    let message = await page.getByTestId('customer-success-message').textContent();
    expect(['Customer created successfully', 'Customer updated successfully']).toContain(message.trim());

     // Add Contact
    await page.getByRole('button', { name: 'Create' }).nth(1).click();
    await page.getByLabel('First Name').fill('John');
    await page.getByLabel('Last Name').fill('Doe');
    await page.getByTestId('contact-save-button').click();
    await page.waitForFunction(() => {
      const el = document.querySelector('[data-testid="customer-success-message"]');
      if (!el) return false;
      const text = el.textContent.trim();
      return ['Contact created successfully', 'Contact updated successfully'].includes(text);
    }, { timeout: 5000 });

    message = await page.getByTestId('customer-success-message').textContent();
    expect(['Contact created successfully', 'Contact updated successfully']).toContain(message.trim());

    await page.waitForFunction(() => {
      const modal = document.querySelector('[data-testid="contact-modal"]');
      return !modal || getComputedStyle(modal).display === 'none' || modal.offsetParent === null;
    });
    await page.waitForSelector('[data-testid="contact-modal"]', { state: 'detached' });

    await page.getByTestId('contact-edit-button').first().click(); 
    await page.getByLabel('Last Name').fill('Smith');
    await page.getByTestId('contact-save-button').click();
    await page.waitForSelector('[data-testid="customer-success-message"]');
    message = await page.getByTestId('customer-success-message').textContent();
    expect(['Contact created successfully', 'Contact updated successfully']).toContain(message.trim());
    await page.waitForFunction(() => {
      const modal = document.querySelector('[data-testid="contact-modal"]');
      return !modal || getComputedStyle(modal).display === 'none' || modal.offsetParent === null;
    });
    // Delete Contact
    await page.getByTestId('contact-delete-button').first().click(); 
    await expect(page.getByRole('heading', { name: 'Confirm Deletion' })).toBeVisible();


    await page.getByRole('button', { name: 'Delete' }).click();

    // Wait for success message
    await page.waitForSelector('[data-testid="customer-success-message"]');
    await page.waitForFunction(() => {
      const el = document.querySelector('[data-testid="customer-success-message"]');
      if (!el) return false;
      const text = el.textContent.trim();
      return ['Contact deleted successfully'].includes(text);
    }, { timeout: 5000 });
    message = await page.getByTestId('customer-success-message').textContent();
    expect(['Contact deleted successfully']).toContain(message.trim());
  });

  test('should return customer in list and allow search & delete', async ({ page }) => {
    await page.getByText('Create', { exact: true }).click();

    await page.getByLabel('Name').fill(updatedCustomerName);
    customerReference = 'REF-' + Date.now();
    await page.getByLabel('Reference').fill(customerReference);
    await page.getByLabel('Category').selectOption('Bronze');
    await page.getByRole('button', { name: 'Save' }).click();
    let message = await page.getByTestId('customer-success-message').textContent();
    expect(['Customer created successfully', 'Customer updated successfully']).toContain(message.trim());

    // Close modal
    await page.getByText('Back', { exact: true }).click();

    // Search and verify
    await page.getByLabel('Search').fill(updatedCustomerName);
    await page.getByText('Apply').click();
    await expect(page.locator('table')).toContainText(updatedCustomerName);

    // Delete
    await page.locator('text=Delete').first().click();
    await page.locator('a:has-text("Delete")').first().click();
    await page.getByRole('button', { name: 'Delete' }).click();
    await expect(page.getByRole('heading', { name: 'Customers' })).toBeVisible();
  });
});
