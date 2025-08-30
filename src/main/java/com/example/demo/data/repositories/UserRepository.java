package com.example.demo.data.repositories;

import java.util.List;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;
import com.example.demo.data.schema.UserSchema;

@Repository
public interface UserRepository extends JpaRepository<UserSchema, Long> {
    List<UserSchema> findByUsernameContaining(String nameFragment);
    List<UserSchema> findByEmailContaining(String emailFragment);
}
