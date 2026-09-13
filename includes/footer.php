            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/admin.js"></script>
    <script>
    setInterval(() => { fetch('<?= BASE_URL ?>/api/ping.php').catch(() => {}); }, 300000);
    </script>
</body>
</html>
