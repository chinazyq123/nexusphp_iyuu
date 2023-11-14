import requests
import hashlib

# 双方提前约定的私有密钥
secret = 'your_secret_key'

# 请求参数
token = 'your_random_token'
user_id = 10001  # 用户在站点的唯一数字ID
passkey = 'passkey'  # 这里应该是从站点获取的用户passkey

# 计算验证字段
verity = hashlib.md5((token + str(user_id) + hashlib.sha1(passkey.encode()).hexdigest() + secret).encode()).hexdigest()

# 接口地址
api_url = 'https://iyuu.ecustpt.eu.org/?token={}&id={}&verity={}'.format(token, user_id, verity)

# 发送HTTP GET请求
response = requests.get(api_url)

# 处理响应
if response.status_code == 200:
    result = response.json()
    success = result.get('success', False)
    if success:
        print("验证成功")
    else:
        error_msg = result.get('msg', '未知错误')
        print(f"验证失败: {error_msg}")
else:
    print(f"请求失败，状态码: {response.status_code}")

# 输出响应文本内容
print(response.text)
