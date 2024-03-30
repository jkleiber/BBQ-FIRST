import json


class CredentialManager:
    def __init__(self, file="credentials.json"):
        with open(file, "r") as f:
            self.creds = json.load(f)

    def get_credential(self, key):
        return self.creds[key]
