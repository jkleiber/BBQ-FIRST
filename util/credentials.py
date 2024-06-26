import json


class CredentialManager:
    def __init__(self, credentials=None, file="private_credentials.json"):
        if credentials is None:
            with open(file, "r") as f:
                self.creds = json.load(f)
        else:
            self.creds = credentials

    def get_credential(self, key):
        return self.creds[key]
    
    def export_credentials(self):
        return json.dumps(self.creds)
