// utils.js - Utility functions for EduSync

/**
 * Loads and parses a CSV file into an array of objects.
 * @param {string} url - The URL of the CSV file.
 * @returns {Promise<Array<Object>>} - A promise that resolves to an array of school objects.
 */
async function loadSchoolsFromCSV(url) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const csvText = await response.text();
        const lines = csvText.trim().split('\n');
        const headers = lines[0].split(',').map(h => h.trim());
        const schools = lines.slice(1).map(line => {
            const values = line.split(',').map(v => v.trim());
            const school = {};
            headers.forEach((header, index) => {
                school[header] = values[index];
            });
            return school;
        });
        return schools;
    } catch (error) {
        console.error('Error loading CSV:', error);
        return [];
    }
}

/**
 * Filters schools based on keywords from the query.
 * @param {Array<Object>} schools - The array of school objects.
 * @param {string} query - The user's query string.
 * @returns {Array<Object>} - Filtered array of schools (up to 5).
 */
function filterSchools(schools, query) {
    const keywords = query.toLowerCase().split(' ').filter(word => word.length > 0);
    const filtered = schools.filter(school => {
        const schoolText = Object.values(school).join(' ').toLowerCase();
        return keywords.some(keyword => schoolText.includes(keyword));
    });
    return filtered.slice(0, 5); // Return up to 5 results
}
